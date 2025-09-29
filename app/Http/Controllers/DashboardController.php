<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class DashboardController extends Controller
{
    public function index()
    {
        // Get products only for the currently logged-in user's shop
        $products = Product::where('shop_id', auth()->user()->shop_id)->get();

        // Send to dashboard view
        return view('dashboard', compact('products'));
    }
}
