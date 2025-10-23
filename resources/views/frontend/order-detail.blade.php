@extends('layouts.app')

@section('title', 'Order Details')

@section('content')
<div class="container my-5">
    <h2 class="mb-4">Order Details #{{ $order->id }}</h2>

    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h5>Order Items</h5>
                </div>
                <div class="card-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Price</th>
                                <th>Quantity</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->orderItems as $item)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @if($item->product->image)
                                            <img src="{{ asset('storage/' . $item->product->image) }}" 
                                                 width="50" class="rounded me-2">
                                        @endif
                                        {{ $item->product->name }}
                                    </div>
                                </td>
                                <td>₹{{ number_format($item->price, 2) }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td>₹{{ number_format($item->price * $item->quantity, 2) }}</td>
                            </tr>
                            @endforeach
                            <tr class="table-active">
                                <td colspan="3" class="text-end"><strong>Total:</strong></td>
                                <td><strong>₹{{ number_format($order->total_amount, 2) }}</strong></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header"><h5>Order Info</h5></div>
                <div class="card-body">
                    <p><strong>Order ID:</strong> #{{ $order->id }}</p>
                    <p><strong>Date:</strong> {{ $order->created_at->format('d M Y') }}</p>
                    <p><strong>Status:</strong> 
                        <span class="badge bg-{{ $order->status == 'Delivered' ? 'success' : 'warning' }}">
                            {{ $order->status }}
                        </span>
                    </p>
                </div>
            </div>

            <div class="card">
                <div class="card-header"><h5>Delivery Address</h5></div>
                <div class="card-body">
                    <p class="mb-0">{{ $order->address->address_line1 }}</p>
                    @if($order->address->address_line2)
                        <p class="mb-0">{{ $order->address->address_line2 }}</p>
                    @endif
                    <p class="mb-0">{{ $order->address->city }}, {{ $order->address->state }}</p>
                    <p class="mb-0">{{ $order->address->pincode }}</p>
                    <p>Phone: {{ $order->address->phone }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection