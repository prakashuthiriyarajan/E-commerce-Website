@extends('layouts.app')

@section('title', 'My Orders')

@section('content')
<div class="container">
    <h2 class="mb-4"><i class="fas fa-box"></i> My Orders</h2>

    @if($orders->isEmpty())
        <div class="alert alert-info text-center">
            <i class="fas fa-box fa-3x mb-3"></i>
            <h4>No orders yet</h4>
            <p>Start shopping and place your first order!</p>
            <a href="{{ route('home') }}" class="btn btn-primary">Start Shopping</a>
        </div>
    @else
        @foreach($orders as $order)
        <div class="card mb-4">
            <div class="card-header">
                <div class="row align-items-center">
                    <div class="col-md-3">
                        <strong>Order #{{ $order->id }}</strong>
                    </div>
                    <div class="col-md-3">
                        <small class="text-muted">{{ $order->created_at->format('d M Y, h:i A') }}</small>
                    </div>
                    <div class="col-md-3">
                        @if($order->status == 'On Process')
                            <span class="badge bg-warning">{{ $order->status }}</span>
                        @elseif($order->status == 'Shipped')
                            <span class="badge bg-info">{{ $order->status }}</span>
                        @else
                            <span class="badge bg-success">{{ $order->status }}</span>
                        @endif
                    </div>
                    <div class="col-md-3 text-end">
                        <strong class="text-primary">₹{{ number_format($order->total_amount, 2) }}</strong>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-8">
                        <h6 class="mb-3">Order Items:</h6>
                        @foreach($order->orderItems as $item)
                        <div class="d-flex mb-2">
                            <div class="me-3">
                                @if($item->product->image)
                                    <img src="{{ asset('storage/' . $item->product->image) }}" width="60" height="60" class="rounded" alt="{{ $item->product->name }}">
                                @endif
                            </div>
                            <div class="flex-grow-1">
                                <p class="mb-0"><strong>{{ $item->product->name }}</strong></p>
                                <p class="text-muted small mb-0">Qty: {{ $item->quantity }} × ₹{{ number_format($item->price, 2) }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <div class="col-md-4">
                        <h6 class="mb-3">Delivery Address:</h6>
                        <p class="mb-0 small">{{ $order->address->address_line1 }}</p>
                        @if($order->address->address_line2)
                            <p class="mb-0 small">{{ $order->address->address_line2 }}</p>
                        @endif
                        <p class="mb-0 small">{{ $order->address->city }}, {{ $order->address->state }}</p>
                        <p class="mb-0 small">{{ $order->address->pincode }}</p>
                        <p class="small">Phone: {{ $order->address->phone }}</p>
                    </div>
                </div>
                <div class="mt-3">
                    <a href="{{ route('user.order.detail', $order->id) }}" class="btn btn-sm btn-outline-primary">
                        View Details
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    @endif
</div>
@endsection