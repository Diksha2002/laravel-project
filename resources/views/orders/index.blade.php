@extends('layouts.app')

@section('content')
<div class="container" style="max-width: 900px; margin: 2rem auto;">

    <h1 style="text-align:center; margin-bottom: 2rem;">Your Orders</h1>

    @if ($orders->isEmpty())
        <p style="text-align:center;">No orders found.</p>
    @else
        <div style="display: flex; flex-wrap: wrap; gap: 1.5rem;">
            @foreach ($orders as $order)
                <div style="flex: 1 1 300px; background-color: #f9fafb; border-radius: 0.75rem; padding: 1.5rem; box-shadow: 0 4px 12px rgba(0,0,0,0.1); border: 1px solid #e5e7eb;">
                    <h3 style="margin-bottom: 0.5rem;">Order #{{ $order->id }}</h3>
                    <p><strong>Placed At:</strong> {{ $order->created_at->format('Y-m-d H:i') }}</p>
                    <p><strong>Total Price:</strong> ₹{{ number_format($order->total_price, 2) }}</p>

                    <h4 style="margin-top: 1rem; font-size: 1rem;">Items:</h4>
                    <ul style="padding-left: 1.25rem;">
                        @foreach ($order->items as $item)
                            <li>
                                {{ $item->product->name ?? 'Unknown Product' }} 
                                – Qty: {{ $item->quantity }} 
                                – ₹{{ number_format($item->price, 2) }}
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endforeach
        </div>
    @endif

</div>
@endsection
