@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Products</h1>

    @if(session('success'))
        <div style="color: green; margin-bottom: 1rem;">
            {{ session('success') }}
        </div>
    @endif

    <a href="{{ route('products.create') }}" style="display: inline-block; margin-bottom: 1rem; font-weight: bold; color: #4f46e5;">Add New Product</a>

    @if($products->count())
    <table border="1" cellpadding="8" cellspacing="0" style="margin-top: 20px; width: 100%; border-collapse: collapse;">
        <thead style="background-color: #f3f4f6;">
            <tr>
                <th>Name</th>
                <th>SKU</th>
                <th>Price (₹)</th>
                <th>Stock</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($products as $product)
            <tr>
                <td>{{ $product->name }}</td>
                <td>{{ $product->sku }}</td>
                <td>{{ number_format($product->price, 2) }}</td>
                <td>{{ $product->stock }}</td>
                <td>
                    <a href="{{ route('products.edit', $product) }}" style="margin-right: 10px; color: #4f46e5;">Edit</a>

                    <form action="{{ route('products.destroy', $product) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button onclick="return confirm('Are you sure to delete this product?')" type="submit" style="background: none; border: none; color: red; cursor: pointer; padding: 0;">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
        <p>No products found.</p>
    @endif
</div>
@endsection
