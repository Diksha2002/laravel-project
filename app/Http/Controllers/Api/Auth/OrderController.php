<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Jobs\SendOrderConfirmationJob;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $orders = Order::with('items.product', 'user', 'shop')
            ->where('shop_id', $user->shop_id)
            ->get();

        return response()->json($orders);
    }

    public function store(Request $request)
    {
        $user = $request->user();
        $shopId = $user->shop_id;

        $request->validate([
            'products' => 'required|array|min:1',
            'products.*.id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
        ]);

        $productsInput = $request->input('products');

        DB::beginTransaction();

        try {
            $totalPrice = 0;
            $orderItems = [];

            $productIds = collect($productsInput)->pluck('id')->toArray();
            $products = Product::where('shop_id', $shopId)
                               ->whereIn('id', $productIds)
                               ->lockForUpdate()
                               ->get();

            if ($products->count() !== count($productIds)) {
                return response()->json(['error' => 'Invalid product(s) for your shop.'], 403);
            }

            foreach ($productsInput as $pInput) {
                $product = $products->where('id', $pInput['id'])->first();
                if ($product->stock < $pInput['quantity']) {
                    return response()->json([
                        'error' => "Insufficient stock for product {$product->name}."
                    ], 400);
                }
                $totalPrice += $product->price * $pInput['quantity'];

                $orderItems[] = [
                    'product_id' => $product->id,
                    'quantity' => $pInput['quantity'],
                    'price' => $product->price,
                ];
            }

            $order = Order::create([
                'shop_id' => $shopId,
                'user_id' => $user->id,
                'total_price' => $totalPrice,
            ]);

            foreach ($orderItems as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                ]);

                $product = $products->where('id', $item['product_id'])->first();
                $product->decrement('stock', $item['quantity']);
            }

            DB::commit();

            SendOrderConfirmationJob::dispatch($order);

            return response()->json(['message' => 'Order placed successfully!', 'order' => $order], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Order failed: '.$e->getMessage()], 500);
        }
    }
}
