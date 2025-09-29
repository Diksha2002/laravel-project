<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductApiController extends Controller
{
 public function __construct()
{
    $this->middleware('auth:sanctum');
}

    public function index(Request $request)
    {
        $shopId = $request->user()->shop_id;
        $products = Product::where('shop_id', $shopId)->get();
        return response()->json(['products' => $products], 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'sku' => 'required|string|unique:products',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
        ]);

        $validated['shop_id'] = $request->user()->shop_id;

        $product = Product::create($validated);

        return response()->json(['product' => $product], 201);
    }

    public function show(Request $request, Product $product)
    {
        if ($product->shop_id !== $request->user()->shop_id) {
            return response()->json(['message' => 'Not authorized'], 403);
        }
        return response()->json(['product' => $product], 200);
    }

    public function update(Request $request, Product $product)
    {
        if ($product->shop_id !== $request->user()->shop_id) {
            return response()->json(['message' => 'Not authorized'], 403);
        }

        $validated = $request->validate([
            'name' => 'sometimes|required|string',
            'sku' => 'sometimes|required|string|unique:products,sku,' . $product->id,
            'price' => 'sometimes|required|numeric',
            'stock' => 'sometimes|required|integer',
        ]);

        $product->update($validated);

        return response()->json(['product' => $product], 200);
    }

    public function destroy(Request $request, Product $product)
    {
        if ($product->shop_id !== $request->user()->shop_id) {
            return response()->json(['message' => 'Not authorized'], 403);
        }
        $product->delete();
        return response()->json(['message' => 'Deleted'], 200);
    }
}
