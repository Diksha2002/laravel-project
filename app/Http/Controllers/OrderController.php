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

    public function index()
    {
        $user = Auth::user();

        $orders = Order::with('items.product')
            ->where('shop_id', $user->shop_id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('orders.index', compact('orders'));
    }

    public function create()
    {
        $products = Product::where('shop_id', Auth::user()->shop_id)->get();
        return view('orders.create', compact('products'));
    }

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

    // NEW: show order details
    public function show($id)
    {
        $user = Auth::user();

        $order = Order::with('items.product', 'user')
            ->where('id', $id)
            ->where('shop_id', $user->shop_id)
            ->firstOrFail();

        return view('orders.show', compact('order'));
    }

    // NEW: update status (including cancel -> restore stock)
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:placed,confirmed,cancelled,completed',
        ]);

        $user = Auth::user();
        DB::beginTransaction();

        try {
            // lock the order and its products
            $order = Order::with('items')->where('id', $id)->where('shop_id', $user->shop_id)->lockForUpdate()->firstOrFail();

            $oldStatus = $order->status;
            $newStatus = $request->input('status');

            if ($oldStatus === $newStatus) {
                DB::commit();
                return redirect()->route('orders.show', $order->id)->with('info', 'No status change.');
            }

            // if cancelling from non-cancelled -> restore stock
            if ($oldStatus !== 'cancelled' && $newStatus === 'cancelled') {
                foreach ($order->items as $item) {
                    $product = Product::where('id', $item->product_id)->lockForUpdate()->first();
                    if ($product) {
                        $product->increment('stock', $item->quantity);
                    }
                }
            }

            // if moving from cancelled -> some other status, deduct stock again (careful)
            if ($oldStatus === 'cancelled' && $newStatus !== 'cancelled') {
                // ensure enough stock exists
                foreach ($order->items as $item) {
                    $product = Product::where('id', $item->product_id)->lockForUpdate()->first();
                    if (!$product || $product->stock < $item->quantity) {
                        DB::rollBack();
                        return back()->withErrors("Cannot change status — insufficient stock to re-confirm for product {$product->name}");
                    }
                }
                // deduct stock
                foreach ($order->items as $item) {
                    $product = Product::where('id', $item->product_id)->lockForUpdate()->first();
                    $product->decrement('stock', $item->quantity);
                }
            }

            $order->status = $newStatus;
            $order->save();

            DB::commit();

            return redirect()->route('orders.show', $order->id)->with('success', 'Order status updated.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors('Status update failed: ' . $e->getMessage());
        }
    }
}
