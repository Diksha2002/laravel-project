<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // <-- don't forget this import

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth'); // Ensure user is authenticated for all actions
    }

    // Show list of products for logged-in user's shop
    public function index()
    {
        $shopId = Auth::user()->shop_id;
        $products = Product::where('shop_id', $shopId)->get();

        return view('products.index', compact('products'));
    }

    // Show form to create a new product
    public function create()
    {
        return view('products.create');
    }

    // Store new product in database, linked to user's shop
    public function store(Request $request)
    {
        $request->validate([
            'name'  => 'required|string|max:255',
            'sku'   => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
        ]);

        $product = Product::create([
            'name'    => $request->name,
            'sku'     => $request->sku,
            'price'   => $request->price,
            'stock'   => $request->stock,
            'shop_id' => Auth::user()->shop_id,
        ]);

        // Redirect back to product list with success message
        return redirect()->route('products.index')->with('success', 'Product added successfully!');
    }

    // Show edit form for a product
    public function edit(Product $product)
    {
        $this->authorizeProduct($product);

        return view('products.edit', compact('product'));
    }

    // Update product data
    public function update(Request $request, Product $product)
    {
        $this->authorizeProduct($product);

        $request->validate([
            'name'  => 'required|string|max:255',
            'sku'   => 'required|string|unique:products,sku,' . $product->id,
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
        ]);

        $product->update([
            'name'  => $request->name,
            'sku'   => $request->sku,
            'price' => $request->price,
            'stock' => $request->stock,
        ]);

        return redirect()->route('products.index')->with('success', 'Product updated successfully.');
    }

    // Delete product
    public function destroy(Product $product)
    {
        $this->authorizeProduct($product);

        $product->delete();

        return redirect()->route('products.index')->with('success', 'Product deleted successfully.');
    }

    // Helper function to check product ownership by shop
    private function authorizeProduct(Product $product)
    {
        if ($product->shop_id !== Auth::user()->shop_id) {
            abort(403, 'Unauthorized action.');
        }
    }
}
