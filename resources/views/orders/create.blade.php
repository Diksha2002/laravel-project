@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2>Create New Order</h2>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
    @endif

    <form action="{{ route('orders.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label>Select Products</label>
            @foreach($products as $product)
                <div class="form-check mb-2">
                    <input type="checkbox" class="form-check-input" name="products[{{ $product->id }}][id]" value="{{ $product->id }}">
                    <label class="form-check-label">
                        {{ $product->name }} - ₹{{ $product->price }} (Stock: {{ $product->stock }})
                    </label>
                    <input type="number" class="form-control mt-1" name="products[{{ $product->id }}][quantity]" placeholder="Quantity" min="1" style="width: 100px;">
                </div>
            @endforeach
        </div>

        <button type="submit" class="btn btn-success">Place Order</button>
        <a href="{{ route('orders.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection
