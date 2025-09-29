{{-- resources/views/products/edit.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Edit Product</h1>

    @if ($errors->any())
        <div style="color: red; margin-bottom: 1rem;">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('products.update', $product) }}" method="POST">
        @csrf
        @method('PUT')

        <div>
            <label for="name">Product Name:</label><br>
            <input type="text" id="name" name="name" value="{{ old('name', $product->name) }}" required>
        </div>

        <div>
            <label for="sku">SKU:</label><br>
            <input type="text" id="sku" name="sku" value="{{ old('sku', $product->sku) }}" required>
        </div>

        <div>
            <label for="price">Price (₹):</label><br>
            <input type="number" id="price" name="price" step="0.01" value="{{ old('price', $product->price) }}" required>
        </div>

        <div>
            <label for="stock">Stock Quantity:</label><br>
            <input type="number" id="stock" name="stock" value="{{ old('stock', $product->stock) }}" required>
        </div>

        <div style="margin-top: 1rem;">
            <button type="submit">Update Product</button>
            <a href="{{ route('products.index') }}" style="margin-left: 1rem;">Cancel</a>
        </div>
    </form>
</div>
@endsection
