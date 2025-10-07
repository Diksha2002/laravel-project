@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2>Order #{{ $order->id }}</h2>

    @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
    @if(session('info')) <div class="alert alert-info">{{ session('info') }}</div> @endif
    @if($errors->any()) <div class="alert alert-danger"><ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>@endif

    <div class="mb-3">
        <strong>Status:</strong> <span class="badge bg-primary">{{ ucfirst($order->status) }}</span>
        <br>
        <strong>Total:</strong> ₹{{ $order->total_price }}
        <br>
        <strong>Placed at:</strong> {{ $order->created_at->format('d M Y h:i A') }}
    </div>

    <h5>Items</h5>
    <ul>
        @foreach($order->items as $item)
            <li>{{ $item->product->name }} — Qty: {{ $item->quantity }} — ₹{{ $item->price }}</li>
        @endforeach
    </ul>

    <hr>

    <form action="{{ route('orders.updateStatus', $order->id) }}" method="POST" class="mb-2">
        @csrf
        @method('PATCH')

        <div class="row g-2 align-items-center">
            <div class="col-auto">
                <label for="status" class="form-label">Change status</label>
            </div>
            <div class="col-auto">
                <select name="status" id="status" class="form-select">
                    <option value="placed" {{ $order->status === 'placed' ? 'selected' : '' }}>Placed</option>
                    <option value="confirmed" {{ $order->status === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                    <option value="completed" {{ $order->status === 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </div>
            <div class="col-auto">
                <button class="btn btn-primary">Update</button>
            </div>
        </div>
    </form>

    <a href="{{ route('orders.index') }}" class="btn btn-secondary">Back to Orders</a>
</div>
@endsection
