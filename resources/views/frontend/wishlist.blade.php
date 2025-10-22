@extends('layouts.app')

@section('title', 'My Wishlist')

@section('content')
<div class="container">
    <h2 class="mb-4"><i class="fas fa-heart text-danger"></i> My Wishlist</h2>

    @if($wishlistItems->isEmpty())
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="alert alert-info text-center py-5">
                    <i class="fas fa-heart fa-4x mb-3 text-muted"></i>
                    <h4>Your wishlist is empty</h4>
                    <p class="text-muted">Add items you love to your wishlist!</p>
                    <a href="{{ route('home') }}" class="btn btn-primary mt-3">
                        <i class="fas fa-shopping-bag"></i> Start Shopping
                    </a>
                </div>
            </div>
        </div>
    @else
        <div class="row">
            @foreach($wishlistItems as $item)
            <div class="col-md-3 mb-4">
                <div class="card product-card h-100">
                    @if($item->product->image)
                        <img src="{{ asset('storage/' . $item->product->image) }}" class="card-img-top" style="height: 200px; object-fit: cover;" alt="{{ $item->product->name }}">
                    @else
                        <div class="card-img-top bg-secondary d-flex align-items-center justify-content-center" style="height: 200px;">
                            <i class="fas fa-image fa-3x text-white"></i>
                        </div>
                    @endif
                    <div class="card-body d-flex flex-column">
                        <h6 class="card-title">{{ Str::limit($item->product->name, 30) }}</h6>
                        <p class="text-muted small mb-2">{{ $item->product->category->name }}</p>
                        <h5 class="text-primary mb-3">₹{{ number_format($item->product->price, 2) }}</h5>
                        
                        @if($item->product->stock_count > 0)
                            <span class="badge bg-success mb-3">In Stock</span>
                        @else
                            <span class="badge bg-danger mb-3">Out of Stock</span>
                        @endif

                        <div class="mt-auto">
                            @if($item->product->stock_count > 0)
                                <form action="{{ route('cart.add', $item->product) }}" method="POST" class="mb-2">
                                    @csrf
                                    <button type="submit" class="btn btn-primary btn-sm w-100">
                                        <i class="fas fa-shopping-cart"></i> Add to Cart
                                    </button>
                                </form>
                            @endif
                            
                            <form action="{{ route('wishlist.remove', $item) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger btn-sm w-100">
                                    <i class="fas fa-trash"></i> Remove
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    @endif
</div>
@endsection