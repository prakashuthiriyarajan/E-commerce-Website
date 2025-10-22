@extends('layouts.app')

@section('content')
<div class="container my-5">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Products</a></li>
            <li class="breadcrumb-item active">{{ $product->name }}</li>
        </ol>
    </nav>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('info'))
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            <i class="fas fa-info-circle"></i> {{ session('info') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <!-- Product Image -->
        <div class="col-md-5">
            <div class="card shadow-sm">
                <div class="card-body p-4">
                    @if($product->image)
                        <img src="{{ asset('storage/' . $product->image) }}" 
                             alt="{{ $product->name }}" 
                             class="img-fluid rounded product-image">
                    @else
                        <img src="https://via.placeholder.com/500x500?text={{ urlencode($product->name) }}" 
                             alt="{{ $product->name }}" 
                             class="img-fluid rounded">
                    @endif
                </div>
            </div>
        </div>

        <!-- Product Details -->
        <div class="col-md-7">
            <div class="card shadow-sm">
                <div class="card-body p-4">
                    <!-- Product Name -->
                    <h1 class="h2 mb-3">{{ $product->name }}</h1>

                    <!-- Category Badge -->
                    <div class="mb-3">
                        <span class="badge bg-primary">{{ $product->category->name }}</span>
                        @if($product->stock_count > 0)
                            <span class="badge bg-success">In Stock</span>
                        @else
                            <span class="badge bg-danger">Out of Stock</span>
                        @endif
                    </div>

                    <!-- Price -->
                    <div class="mb-4">
                        <h2 class="h3 text-primary mb-0">
                            ₹{{ number_format($product->price, 2) }}
                        </h2>
                    </div>

                    <!-- Description -->
                    <div class="mb-4">
                        <h5>Description</h5>
                        <p class="text-muted">{{ $product->description }}</p>
                    </div>

                    <!-- Availability -->
                    <div class="mb-4">
                        <h6>Availability:</h6>
                        @if($product->stock_count > 0)
                            <p class="text-success mb-0">
                                <i class="fas fa-check-circle"></i> 
                                <strong>{{ $product->stock_count }} items in stock</strong>
                            </p>
                        @else
                            <p class="text-danger mb-0">
                                <i class="fas fa-times-circle"></i> 
                                <strong>Out of Stock</strong>
                            </p>
                        @endif
                    </div>

                    <!-- Action Buttons -->
                    <div class="d-flex flex-wrap gap-2 mb-3">
                        @auth
                            @if($product->stock_count > 0)
                                <!-- Buy Now Button (Primary) -->
                                <form action="{{ route('cart.buyNow', $product->id) }}" method="POST" class="flex-grow-1">
                                    @csrf
                                    <button type="submit" class="btn btn-success btn-lg w-100">
                                        <i class="fas fa-bolt"></i> Buy Now
                                    </button>
                                </form>

                                <!-- Add to Cart Button -->
                                <form action="{{ route('cart.add', $product->id) }}" method="POST" class="flex-grow-1">
                                    @csrf
                                    <button type="submit" class="btn btn-primary btn-lg w-100">
                                        <i class="fas fa-shopping-cart"></i> Add to Cart
                                    </button>
                                </form>

                                <!-- Add to Wishlist Button -->
                                <form action="{{ route('wishlist.add', $product->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-outline-danger btn-lg">
                                        <i class="fas fa-heart"></i> Wishlist
                                    </button>
                                </form>
                            @else
                                <button class="btn btn-secondary btn-lg w-100" disabled>
                                    <i class="fas fa-times-circle"></i> Out of Stock
                                </button>
                            @endif
                        @else
                            <a href="{{ route('login') }}" class="btn btn-primary btn-lg w-100">
                                <i class="fas fa-sign-in-alt"></i> Login to Purchase
                            </a>
                        @endauth
                    </div>

                    <!-- Additional Info -->
                    <div class="card bg-light mt-4">
                        <div class="card-body">
                            <h6 class="mb-3">Product Information</h6>
                            <ul class="list-unstyled mb-0">
                                <li class="mb-2">
                                    <i class="fas fa-tag text-primary"></i> 
                                    <strong>Category:</strong> {{ $product->category->name }}
                                </li>
                                <li class="mb-2">
                                    <i class="fas fa-barcode text-primary"></i> 
                                    <strong>Product ID:</strong> #{{ $product->id }}
                                </li>
                                <li class="mb-2">
                                    <i class="fas fa-truck text-primary"></i> 
                                    <strong>Delivery:</strong> Free shipping on orders above ₹500
                                </li>
                                <li>
                                    <i class="fas fa-undo text-primary"></i> 
                                    <strong>Returns:</strong> 7 days return policy
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.product-image {
    transition: transform 0.3s ease;
}

.product-image:hover {
    transform: scale(1.05);
}

.gap-2 {
    gap: 0.5rem !important;
}

.btn-lg {
    padding: 12px 24px;
    font-size: 1.1rem;
}

.card {
    border: none;
}

.badge {
    padding: 8px 16px;
    font-size: 0.9rem;
}
</style>
@endsection