<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Jobs\SendOrderConfirmationJob;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // Show list of all orders for this shop
    public function index()
    {
        $user = Auth::user();

        $orders = Order::with('items.product')
            ->where('shop_id', $user->shop_id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('orders.index', compact('orders'));
    }

    // Show form to create a new order
    public function create()
    {
        $products = Product::where('shop_id', Auth::user()->shop_id)->get();
        return view('orders.create', compact('products'));
    }

    // Store new order
    public function store(Request $request)
    {
        $request->validate([
            'products' => 'required|array|min:1',
            'products.*.id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
        ]);

        $user = Auth::user();
        $shopId = $user->shop_id;
        $productsInput = $request->input('products');

        DB::beginTransaction();
        try {
            $productIds = collect($productsInput)->pluck('id')->toArray();

            $products = Product::where('shop_id', $shopId)
                ->whereIn('id', $productIds)
                ->lockForUpdate()
                ->get();

            if ($products->count() !== count($productIds)) {
                return back()->withErrors('Invalid products for this shop.');
            }

            $totalPrice = 0;

            foreach ($productsInput as $p) {
                $prod = $products->where('id', $p['id'])->first();
                if ($prod->stock < $p['quantity']) {
                    DB::rollBack();
                    return back()->withErrors("Insufficient stock for: {$prod->name}");
                }
                $totalPrice += $prod->price * $p['quantity'];
            }

            $order = Order::create([
                'shop_id' => $shopId,
                'user_id' => $user->id,
                'total_price' => $totalPrice,
                'status' => 'placed',
            ]);

            foreach ($productsInput as $p) {
                $prod = $products->where('id', $p['id'])->first();

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $prod->id,
                    'quantity' => $p['quantity'],
                    'price' => $prod->price,
                ]);

                $prod->decrement('stock', $p['quantity']);
            }

            DB::commit();

            SendOrderConfirmationJob::dispatch($order);

            return redirect()->route('orders.index')->with('success', 'Order placed successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors('Order failed: ' . $e->getMessage());
        }
    }
}
