@extends('layouts.app')

@section('title', $product->name)

@section('content')
<div class="container">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item active">{{ $product->name }}</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-md-6 mb-4">
            @if($product->image)
                <img src="{{ asset('storage/' . $product->image) }}" class="img-fluid rounded shadow" alt="{{ $product->name }}">
            @else
                <div class="bg-secondary rounded d-flex align-items-center justify-content-center" style="height: 400px;">
                    <i class="fas fa-image fa-5x text-white"></i>
                </div>
            @endif
        </div>

        <div class="col-md-6">
            <h2 class="mb-3">{{ $product->name }}</h2>
            
            <div class="mb-3">
                <span class="badge bg-info">{{ $product->category->name }}</span>
                @if($product->stock_count > 0)
                    <span class="badge bg-success">In Stock</span>
                @else
                    <span class="badge bg-danger">Out of Stock</span>
                @endif
            </div>

            <h3 class="text-primary mb-4">₹{{ number_format($product->price, 2) }}</h3>

            <div class="mb-4">
                <h5>Description</h5>
                <p class="text-muted">{{ $product->description ?? 'No description available.' }}</p>
            </div>

            <div class="mb-4">
                <p><strong>Availability:</strong> 
                    @if($product->stock_count > 0)
                        <span class="text-success">{{ $product->stock_count }} items in stock</span>
                    @else
                        <span class="text-danger">Currently out of stock</span>
                    @endif
                </p>
            </div>

            @auth
                @if($product->stock_count > 0)
                    <div class="d-grid gap-2 d-md-block">
                        <form action="{{ route('cart.add', $product) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-primary btn-lg me-2">
                                <i class="fas fa-shopping-cart"></i> Add to Cart
                            </button>
                        </form>

                        <!-- Buy Now Button -->
            <form action="{{ route('cart.buyNow', $product->id) }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-success btn-lg">
                    <i class="fas fa-bolt"></i> Buy Now
                </button>
            </form>
                        
                        <form action="{{ route('wishlist.add', $product) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-outline-danger btn-lg">
                                <i class="fas fa-heart"></i> Add to Wishlist
                            </button>
                        </form>
                    </div>
                @else
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle"></i> This product is currently out of stock. Cart and wishlist options are disabled.
                    </div>
                @endif
            @else
                <div class="alert alert-info">
                    <a href="{{ route('google.redirect') }}" class="btn btn-primary">
                        <i class="fab fa-google"></i> Login with Google to Purchase
                    </a>
                </div>
            @endauth
        </div>
    </div>
</div>
@endsection