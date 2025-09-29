@extends('layouts.app')

@section('content')
    <style>
        body {
            background-color: #f0f4f8;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .product-form-wrapper {
            max-width: 600px;
            margin: 3rem auto;
            padding: 2.5rem;
            background-color: #f9fafb;
            border-radius: 0.75rem;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.06);
            border: 1px solid #e5e7eb;
        }

        .product-form-wrapper h1 {
            font-size: 1.75rem;
            font-weight: 700;
            color: #1f2937;
            text-align: center;
            margin-bottom: 2rem;
        }

        .form-group {
            margin-bottom: 1.25rem;
        }

        label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            font-size: 0.95rem;
            color: #374151;
        }

        input[type="text"],
        input[type="number"] {
            padding: 0.75rem 1rem;
            border: 1px solid #d1d5db;
            border-radius: 0.5rem;
            font-size: 0.95rem;
            background-color: #ffffff;
            width: 100%;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }

        input:focus {
            outline: none;
            border-color: #4f46e5;
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.2);
        }

        .error-messages {
            margin-bottom: 1rem;
            padding: 1rem;
            background-color: #fee2e2;
            border: 1px solid #fecaca;
            border-radius: 0.5rem;
            color: #b91c1c;
            font-size: 0.9rem;
        }

        .primary-btn {
            display: inline-block;
            background-color: #4f46e5;
            color: white;
            padding: 0.75rem 1.25rem;
            border: none;
            border-radius: 0.5rem;
            font-weight: 600;
            font-size: 0.95rem;
            cursor: pointer;
            transition: background-color 0.3s ease, box-shadow 0.3s ease;
        }

        .primary-btn:hover {
            background-color: #4338ca;
            box-shadow: 0 4px 12px rgba(67, 56, 202, 0.3);
        }

        .back-link {
            display: inline-block;
            margin-top: 1.5rem;
            font-size: 0.9rem;
            color: #4f46e5;
            text-decoration: underline;
        }

        .back-link:hover {
            color: #3730a3;
        }
    </style>

    <div class="product-form-wrapper">
        <h1>Add New Product</h1>

        @if ($errors->any())
            <div class="error-messages">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form action="{{ route('products.store') }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="name">Product Name</label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" required>
            </div>

            <div class="form-group">
                <label for="sku">SKU</label>
                <input type="text" name="sku" id="sku" value="{{ old('sku') }}" required>
            </div>

            <div class="form-group">
                <label for="price">Price (₹)</label>
                <input type="number" step="0.01" name="price" id="price" value="{{ old('price') }}" required>
            </div>

            <div class="form-group">
                <label for="stock">Stock Quantity</label>
                <input type="number" name="stock" id="stock" value="{{ old('stock', 0) }}" required>
            </div>

            <div class="form-group">
                <button type="submit" class="primary-btn">Add Product</button>
            </div>
        </form>

<a href="{{ route('products.index') }}">← Back to Products</a>
    </div>
@endsection
