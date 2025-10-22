@extends('layouts.app')

@section('title', 'Home - E-Commerce Platform')

@section('content')
<div class="container">
    <!-- Hero Section -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="p-5 rounded" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <div class="text-white text-center">
                    <h1 class="display-4 fw-bold">Welcome to E-Shop</h1>
                    <p class="lead">Discover amazing products at unbeatable prices</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Search and Filter -->
    <div class="row mb-4">
        <div class="col-12">
            <form action="{{ route('home') }}" method="GET" class="row g-3">
                <div class="col-md-5">
                    <input type="text" name="search" class="form-control" placeholder="Search products..." value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <select name="category" class="form-select">
                        <option value="">All Categories</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="sort" class="form-select">
                        <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Newest First</option>
                        <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Price: Low to High</option>
                        <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Price: High to Low</option>
                    </select>
                </div>
                <div class="col-md-1">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Products Grid -->
    <div class="row">
        @forelse($products as $product)
        <div class="col-md-3 mb-4">
            <div class="card product-card">
                @if($product->image)
                    <img src="{{ asset('storage/' . $product->image) }}" class="card-img-top product-img" alt="{{ $product->name }}">
                @else
                    <div class="card-img-top product-img bg-secondary d-flex align-items-center justify-content-center">
                        <i class="fas fa-image fa-3x text-white"></i>
                    </div>
                @endif
                <div class="card-body">
                    <h6 class="card-title">{{ Str::limit($product->name, 30) }}</h6>
                    <p class="text-muted small mb-2">{{ $product->category->name }}</p>
                    <h5 class="text-primary">₹{{ number_format($product->price, 2) }}</h5>
                    
                    @if($product->stock_count > 0)
                        <span class="badge bg-success mb-2">In Stock ({{ $product->stock_count }})</span>
                    @else
                        <span class="badge bg-danger mb-2">Out of Stock</span>
                    @endif
                    
                    <div class="d-grid gap-2">
                        <a href="{{ route('products.show', $product) }}" class="btn btn-outline-primary btn-sm">
                            View Details
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="alert alert-info text-center">
                <i class="fas fa-info-circle me-2"></i>No products found matching your criteria.
            </div>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="row">
        <div class="col-12">
            {{ $products->links() }}
        </div>
    </div>
</div>
@endsection