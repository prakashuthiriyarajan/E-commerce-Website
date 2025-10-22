@extends('layouts.app')

@section('title', 'Shopping Cart')

@section('content')
<div class="container">
    <h2 class="mb-4"><i class="fas fa-shopping-cart"></i> Shopping Cart</h2>

    @if($cartItems->isEmpty())
        <div class="alert alert-info text-center">
            <i class="fas fa-shopping-cart fa-3x mb-3"></i>
            <h4>Your cart is empty</h4>
            <p>Start shopping and add items to your cart!</p>
            <a href="{{ route('home') }}" class="btn btn-primary">Continue Shopping</a>
        </div>
    @else
        <div class="row">
            <div class="col-md-8">
                <div class="card mb-4">
                    <div class="card-body">
                        @foreach($cartItems as $item)
                        <div class="row mb-3 pb-3 border-bottom">
                            <div class="col-md-2">
                                @if($item->product->image)
                                    <img src="{{ asset('storage/' . $item->product->image) }}" class="img-fluid rounded" alt="{{ $item->product->name }}">
                                @else
                                    <div class="bg-secondary rounded" style="width:80px;height:80px;"></div>
                                @endif
                            </div>
                            <div class="col-md-4">
                                <h6>{{ $item->product->name }}</h6>
                                <p class="text-muted small">{{ $item->product->category->name }}</p>
                                <p class="text-primary fw-bold">₹{{ number_format($item->product->price, 2) }}</p>
                            </div>
                            <div class="col-md-3">
                                <form action="{{ route('cart.update', $item) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <div class="input-group">
                                        <input type="number" name="quantity" class="form-control" value="{{ $item->quantity }}" min="1" max="{{ $item->product->stock_count }}">
                                        <button type="submit" class="btn btn-outline-primary">
                                            <i class="fas fa-sync"></i>
                                        </button>
                                    </div>
                                </form>
                                <small class="text-muted">Max: {{ $item->product->stock_count }}</small>
                            </div>
                            <div class="col-md-2">
                                <p class="fw-bold">₹{{ number_format($item->product->price * $item->quantity, 2) }}</p>
                            </div>
                            <div class="col-md-1">
                                <form action="{{ route('cart.remove', $item) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Remove this item?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card">
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
                        <a href="{{ route('checkout.index') }}" class="btn btn-primary w-100 btn-lg">
                            Proceed to Checkout <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection