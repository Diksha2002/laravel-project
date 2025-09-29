@extends('layouts.app')

@section('content')
<div class="container" style="max-width: 600px; margin: 2rem auto;">
    <h1>Place a New Order</h1>

    <div id="order-message" style="margin-bottom: 1rem;"></div>

    <form id="order-form">
        @csrf
        <div class="form-group">
            <label for="products">Select Products:</label>
            <select id="products" name="products[]" multiple style="width:100%; padding:0.5rem;">
                @foreach($products as $product)
                    <option value="{{ $product->id }}" data-price="{{ $product->price }}">{{ $product->name }} – ₹{{ number_format($product->price, 2) }} – Stock: {{ $product->stock }}</option>
                @endforeach
            </select>
        </div>

        <div id="quantities"></div>

        <button type="submit" class="primary-btn" style="margin-top:1rem;">Place Order</button>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const productsSelect = document.getElementById('products');
    const quantitiesDiv = document.getElementById('quantities');
    const orderForm = document.getElementById('order-form');
    const messageDiv = document.getElementById('order-message');

    productsSelect.addEventListener('change', function() {
        quantitiesDiv.innerHTML = '';
        Array.from(this.selectedOptions).forEach(option => {
            const qtyInput = document.createElement('input');
            qtyInput.type = 'number';
            qtyInput.name = `quantity_${option.value}`;
            qtyInput.placeholder = `Quantity for ${option.text}`;
            qtyInput.min = 1;
            qtyInput.value = 1;
            qtyInput.style.marginBottom = '0.5rem';
            qtyInput.style.display = 'block';
            quantitiesDiv.appendChild(qtyInput);
        });
    });

    orderForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        const selectedOptions = Array.from(productsSelect.selectedOptions);
        if(!selectedOptions.length) {
            messageDiv.innerHTML = '<p style="color:red;">Select at least one product</p>';
            return;
        }

        const products = selectedOptions.map(option => ({
            id: option.value,
            quantity: parseInt(document.querySelector(`input[name="quantity_${option.value}"]`).value)
        }));

        const token = document.querySelector('input[name="_token"]').value;

        const response = await fetch('/api/orders', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': token
            },
            body: JSON.stringify({ products })
        });

        const data = await response.json();

        if(response.ok){
            messageDiv.innerHTML = `<p style="color:green;">${data.message}</p>`;
            setTimeout(() => window.location.href = '/orders', 1500);
        } else {
            messageDiv.innerHTML = `<p style="color:red;">${data.error}</p>`;
        }
    });
});
</script>
@endsection
