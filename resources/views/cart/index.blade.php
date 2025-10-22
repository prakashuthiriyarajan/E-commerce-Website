@extends('layouts.app')

@section('content')
<div class="container my-5">
    <h1 class="mb-4">
        <i class="fas fa-shopping-cart"></i> Shopping Cart
    </h1>

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

    @if($cartItems->count() > 0)
        <div class="row">
            <!-- Cart Items -->
            <div class="col-lg-8">
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fas fa-shopping-bag"></i> Cart Items ({{ $cartItems->count() }})</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Product</th>
                                        <th>Price</th>
                                        <th>Quantity</th>
                                        <th>Subtotal</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($cartItems as $item)
                                    <tr>
                                        <!-- Product Info -->
                                        <td>
                                            <div class="d-flex align-items-center">
                                                @if($item->product->image)
                                                    <img src="{{ asset('storage/' . $item->product->image) }}" 
                                                         alt="{{ $item->product->name }}" 
                                                         class="img-thumbnail me-3" 
                                                         style="width: 80px; height: 80px; object-fit: cover;">
                                                @else
                                                    <img src="https://via.placeholder.com/80" 
                                                         alt="{{ $item->product->name }}" 
                                                         class="img-thumbnail me-3">
                                                @endif
                                                <div>
                                                    <h6 class="mb-1">
                                                        <a href="{{ route('products.show', $item->product->id) }}" 
                                                           class="text-decoration-none text-dark">
                                                            {{ $item->product->name }}
                                                        </a>
                                                    </h6>
                                                    <small class="text-muted">
                                                        <i class="fas fa-tag"></i> {{ $item->product->category->name }}
                                                    </small>
                                                    @if($item->product->stock_count < 10 && $item->product->stock_count > 0)
                                                        <br>
                                                        <small class="text-warning">
                                                            <i class="fas fa-exclamation-triangle"></i> 
                                                            Only {{ $item->product->stock_count }} left in stock
                                                        </small>
                                                    @elseif($item->product->stock_count == 0)
                                                        <br>
                                                        <small class="text-danger">
                                                            <i class="fas fa-times-circle"></i> Out of stock
                                                        </small>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>

                                        <!-- Price -->
                                        <td class="align-middle">
                                            <strong>₹{{ number_format($item->product->price, 2) }}</strong>
                                        </td>

                                        <!-- Quantity -->
                                        <td class="align-middle">
                                            <form action="{{ route('cart.update', $item->id) }}" 
                                                  method="POST" 
                                                  class="d-inline quantity-form">
                                                @csrf
                                                @method('PATCH')
                                                <div class="input-group" style="width: 130px;">
                                                    <button type="button" class="btn btn-outline-secondary btn-sm" 
                                                            onclick="decrementQuantity(this)">
                                                        <i class="fas fa-minus"></i>
                                                    </button>
                                                    <input type="number" 
                                                           name="quantity" 
                                                           value="{{ $item->quantity }}" 
                                                           min="1" 
                                                           max="{{ $item->product->stock_count }}"
                                                           class="form-control form-control-sm text-center quantity-input"
                                                           onchange="this.form.submit()">
                                                    <button type="button" class="btn btn-outline-secondary btn-sm" 
                                                            onclick="incrementQuantity(this, {{ $item->product->stock_count }})">
                                                        <i class="fas fa-plus"></i>
                                                    </button>
                                                </div>
                                            </form>
                                        </td>

                                        <!-- Subtotal -->
                                        <td class="align-middle">
                                            <strong class="text-primary">
                                                ₹{{ number_format($item->product->price * $item->quantity, 2) }}
                                            </strong>
                                        </td>

                                        <!-- Remove Button -->
                                        <td class="align-middle">
                                            <form action="{{ route('cart.remove', $item->id) }}" 
                                                  method="POST" 
                                                  onsubmit="return confirm('Remove this item from cart?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Continue Shopping -->
                <a href="{{ route('home') }}" class="btn btn-outline-primary">
                    <i class="fas fa-arrow-left"></i> Continue Shopping
                </a>
            </div>

            <!-- Order Summary -->
            <div class="col-lg-4">
                <div class="card shadow-sm sticky-top" style="top: 100px;">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0"><i class="fas fa-calculator"></i> Order Summary</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Subtotal:</span>
                            <strong>₹{{ number_format($subtotal, 2) }}</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Shipping:</span>
                            <span class="text-success">
                                @if($subtotal > 500)
                                    <i class="fas fa-check-circle"></i> Free
                                @else
                                    ₹50.00
                                @endif
                            </span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Tax (18%):</span>
                            <strong>₹{{ number_format($subtotal * 0.18, 2) }}</strong>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between mb-3">
                            <h5>Total:</h5>
                            <h5 class="text-success">
                                ₹{{ number_format($subtotal + ($subtotal * 0.18) + ($subtotal > 500 ? 0 : 50), 2) }}
                            </h5>
                        </div>

                        @if($subtotal < 500)
                            <div class="alert alert-info small mb-3">
                                <i class="fas fa-info-circle"></i> 
                                Add ₹{{ number_format(500 - $subtotal, 2) }} more for free shipping!
                            </div>
                        @endif

                        <a href="{{ route('checkout.index') }}" class="btn btn-success btn-lg w-100 mb-2">
                            <i class="fas fa-lock"></i> Proceed to Checkout
                        </a>

                        <div class="text-center small text-muted mt-3">
                            <i class="fas fa-shield-alt"></i> Secure Checkout
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        <!-- Empty Cart -->
        <div class="text-center py-5">
            <div class="mb-4">
                <i class="fas fa-shopping-cart fa-5x text-muted"></i>
            </div>
            <h3 class="mb-3">Your Cart is Empty</h3>
            <p class="text-muted mb-4">Looks like you haven't added any items to your cart yet.</p>
            <a href="{{ route('home') }}" class="btn btn-primary btn-lg">
                <i class="fas fa-shopping-bag"></i> Start Shopping
            </a>
        </div>
    @endif
</div>

<!-- JavaScript for Quantity Controls -->
<script>
function incrementQuantity(btn, maxStock) {
    const input = btn.previousElementSibling;
    const currentValue = parseInt(input.value);
    if (currentValue < maxStock) {
        input.value = currentValue + 1;
        input.form.submit();
    } else {
        alert('Maximum stock limit reached!');
    }
}

function decrementQuantity(btn) {
    const input = btn.nextElementSibling;
    const currentValue = parseInt(input.value);
    if (currentValue > 1) {
        input.value = currentValue - 1;
        input.form.submit();
    }
}
</script>

<style>
.quantity-input {
    -moz-appearance: textfield;
}
.quantity-input::-webkit-outer-spin-button,
.quantity-input::-webkit-inner-spin-button {
    -webkit-appearance: none;
    margin: 0;
}
.sticky-top {
    position: sticky;
}
</style>
@endsection
```