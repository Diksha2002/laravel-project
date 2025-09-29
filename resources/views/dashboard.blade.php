@extends('layouts.app')

@section('content')
<div class="container" style="margin-top: 20px;">
    {{-- Header with Shop Name, Owner Name and Logout --}}
    <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #ccc; padding-bottom: 15px; margin-bottom: 20px;">
        <div>
            <h1 style="font-size: 24px; font-weight: bold;">📦 Your Shop Products</h1>
            <p style="color: #555;">
                Shop: <strong>{{ auth()->user()->shop->name ?? 'No Shop' }}</strong> | 
                Owner: <strong>{{ auth()->user()->name }}</strong>
            </p>
        </div>

        <div>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:inline;">
                @csrf
                <button type="submit" style="
                    background-color: #dc3545; 
                    border: none; 
                    color: white; 
                    padding: 8px 16px; 
                    font-weight: 600; 
                    border-radius: 4px; 
                    cursor: pointer;
                    transition: background-color 0.3s;">
                    Logout
                </button>
            </form>
        </div>
    </div>

    <a href="{{ route('products.create') }}" 
       style="
           background-color: #007bff; 
           color: white; 
           padding: 10px 20px; 
           border-radius: 4px; 
           font-weight: 600; 
           text-decoration: none;
           display: inline-block;
           margin-bottom: 15px;
           transition: background-color 0.3s;">
        + Add Product
    </a>
    <a href="{{ route('orders.index') }}" 
    style="
        background-color: #28a745; 
        color: white; 
        padding: 10px 20px; 
        border-radius: 4px; 
        font-weight: 600; 
        text-decoration: none;
        display: inline-block;
        margin-left: 10px;
        transition: background-color 0.3s;">
        🛒 View Orders
    </a>

    
    @if (count($products))
        <table style="width: 100%; border-collapse: collapse; border: 1px solid #dee2e6;">
            <thead style="background-color: #f8f9fa;">
                <tr>
                    <th style="border: 1px solid #dee2e6; padding: 10px; text-align: left;">Name</th>
                    <th style="border: 1px solid #dee2e6; padding: 10px; text-align: left;">SKU</th>
                    <th style="border: 1px solid #dee2e6; padding: 10px; text-align: left;">Price</th>
                    <th style="border: 1px solid #dee2e6; padding: 10px; text-align: left;">Stock</th>
                    <th style="border: 1px solid #dee2e6; padding: 10px; text-align: left;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($products as $product)
                <tr style="border: 1px solid #dee2e6;">
                    <td style="border: 1px solid #dee2e6; padding: 10px;">{{ $product->name }}</td>
                    <td style="border: 1px solid #dee2e6; padding: 10px;">{{ $product->sku }}</td>
                    <td style="border: 1px solid #dee2e6; padding: 10px;">₹{{ $product->price }}</td>
                    <td style="border: 1px solid #dee2e6; padding: 10px;">{{ $product->stock }}</td>
                    <td style="border: 1px solid #dee2e6; padding: 10px;">
                        <a href="{{ route('products.edit', $product) }}" 
                           style="
                              background-color: #ffc107; 
                              color: black; 
                              padding: 6px 12px; 
                              border-radius: 4px; 
                              font-weight: 600; 
                              text-decoration: none;
                              margin-right: 8px;">
                            Edit
                        </a>
                        <form action="{{ route('products.destroy', $product) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button 
                                onclick="return confirm('Delete this product?')" 
                                style="
                                    background-color: #dc3545; 
                                    border: none; 
                                    color: white; 
                                    padding: 6px 12px; 
                                    border-radius: 4px; 
                                    font-weight: 600; 
                                    cursor: pointer;">
                                Delete
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p>No products found for your shop.</p>
    @endif
</div>
@endsection
