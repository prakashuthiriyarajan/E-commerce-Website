@extends('layouts.app')

@section('title', 'Checkout')

@section('content')
<div class="container">
    <h2 class="mb-4"><i class="fas fa-credit-card"></i> Checkout</h2>

    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Delivery Address</h5>
                    <a href="{{ route('addresses.create') }}" class="btn btn-sm btn-primary">
                        <i class="fas fa-plus"></i> Add New Address
                    </a>
                </div>
                <div class="card-body">
                    @if($addresses->isEmpty())
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle"></i> Please add a delivery address to proceed with checkout.
                            <a href="{{ route('addresses.create') }}" class="btn btn-sm btn-primary ms-2">Add Address</a>
                        </div>
                    @else
                        <form action="{{ route('checkout.place-order') }}" method="POST">
                            @csrf
                            
                            @foreach($addresses as $address)
                            <div class="form-check mb-3 p-3 border rounded">
                                <input class="form-check-input" type="radio" name="address_id" id="address{{ $address->id }}" value="{{ $address->id }}" required {{ $loop->first ? 'checked' : '' }}>
                                <label class="form-check-label w-100" for="address{{ $address->id }}">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <p class="mb-1"><strong>{{ $address->address_line1 }}</strong></p>
                                            @if($address->address_line2)
                                                <p class="mb-1">{{ $address->address_line2 }}</p>
                                            @endif
                                            <p class="mb-1">{{ $address->city }}, {{ $address->state }} - {{ $address->pincode }}</p>
                                            <p class="mb-0 text-muted">Phone: {{ $address->phone }}</p>
                                        </div>
                                        <div>
                                            <a href="{{ route('addresses.edit', $address) }}" class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        </div>
                                    </div>
                                </label>
                            </div>
                            @endforeach

                            <button type="submit" class="btn btn-success btn-lg w-100 mt-3">
                                <i class="fas fa-check"></i> Place Order
                            </button>
                        </form>
                    @endif
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5>Order Items</h5>
                </div>
                <div class="card-body">
                    @foreach($cartItems as $item)
                    <div class="d-flex mb-3 pb-3 border-bottom">
                        @if($item->product->image)
                            <img src="{{ asset('storage/' . $item->product->image) }}" class="rounded me-3" width="60" height="60" alt="{{ $item->product->name }}">
                        @endif
                        <div class="flex-grow-1">
                            <h6 class="mb-1">{{ $item->product->name }}</h6>
                            <p class="text-muted small mb-0">Quantity: {{ $item->quantity }}</p>
                        </div>
                        <div class="text-end">
                            <p class="mb-0 fw-bold">₹{{ number_format($item->product->price * $item->quantity, 2) }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card sticky-top" style="top: 20px;">
                <div class="card-header">
                    <h5>Order Summary</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Subtotal:</span>
                        <span>₹{{ number_format($subtotal, 2) }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Shipping:</span>
                        <span class="text-success">FREE</span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between mb-3">
                        <strong>Total:</strong>
                        <strong class="text-primary">₹{{ number_format($subtotal, 2) }}</strong>
                    </div>
                    
                    <div class="alert alert-info small">
                        <i class="fas fa-info-circle"></i> Payment will be processed on delivery (COD)
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection