@extends('layouts.app')

@section('content')
<div class="container my-5">
    <h1 class="mb-4">
        <i class="fas fa-credit-card"></i> Checkout
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

    <div class="row">
        <!-- Left Column - Products & Address -->
        <div class="col-lg-8">
            <!-- Products Section -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-shopping-bag"></i> Order Items ({{ $checkoutItems->count() }})
                    </h5>
                </div>
                <div class="card-body">
                    @foreach($checkoutItems as $item)
                    <div class="row align-items-center border-bottom pb-3 mb-3">
                        <!-- Product Image -->
                        <div class="col-md-2">
                            @if($item->product->image)
                                <img src="{{ asset('storage/' . $item->product->image) }}" 
                                     alt="{{ $item->product->name }}" 
                                     class="img-fluid rounded"
                                     style="max-height: 100px; object-fit: cover;">
                            @else
                                <img src="https://via.placeholder.com/100" 
                                     alt="{{ $item->product->name }}" 
                                     class="img-fluid rounded">
                            @endif
                        </div>

                        <!-- Product Details -->
                        <div class="col-md-6">
                            <h6 class="mb-1">{{ $item->product->name }}</h6>
                            <p class="text-muted mb-1">
                                <small>
                                    <i class="fas fa-tag"></i> {{ $item->product->category->name }}
                                </small>
                            </p>
                            <p class="mb-0">
                                <strong>Price:</strong> ₹{{ number_format($item->product->price, 2) }}
                            </p>
                        </div>

                        <!-- Quantity & Subtotal -->
                        <div class="col-md-4 text-end">
                            <p class="mb-1">
                                <strong>Quantity:</strong> {{ $item->quantity }}
                            </p>
                            <p class="mb-0 text-primary h5">
                                ₹{{ number_format($item->product->price * $item->quantity, 2) }}
                            </p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Address Selection -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-map-marker-alt"></i> Select Delivery Address
                    </h5>
                    <button type="button" class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#addAddressModal">
                        <i class="fas fa-plus"></i> Add New
                    </button>
                </div>
                <div class="card-body">
                    @if($addresses->count() > 0)
                        <form action="{{ route('checkout.place-order') }}" method="POST" id="checkoutForm">
                            @csrf
                            @foreach($addresses as $address)
                            <div class="form-check border rounded p-3 mb-3">
                                <input class="form-check-input" 
                                       type="radio" 
                                       name="address_id" 
                                       id="address{{ $address->id }}" 
                                       value="{{ $address->id }}"
                                       {{ $loop->first ? 'checked' : '' }}
                                       required>
                                <label class="form-check-label w-100" for="address{{ $address->id }}">
                                    <strong>{{ $address->address_line1 }}</strong><br>
                                    @if($address->address_line2)
                                        {{ $address->address_line2 }}<br>
                                    @endif
                                    {{ $address->city }}, {{ $address->state }} - {{ $address->pincode }}<br>
                                    <small class="text-muted">
                                        <i class="fas fa-phone"></i> {{ $address->phone }}
                                    </small>
                                </label>
                            </div>
                            @endforeach
                        </form>
                    @else
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle"></i> 
                            No addresses found. Please add a delivery address.
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Right Column - Order Summary -->
        <div class="col-lg-4">
            <div class="card shadow-sm sticky-top" style="top: 100px;">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-calculator"></i> Order Summary
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Subtotal:</span>
                        <strong>₹{{ number_format($subtotal, 2) }}</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Shipping:</span>
                        <span class="{{ $shipping == 0 ? 'text-success' : '' }}">
                            @if($shipping == 0)
                                <i class="fas fa-check-circle"></i> Free
                            @else
                                ₹{{ number_format($shipping, 2) }}
                            @endif
                        </span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Tax (18%):</span>
                        <strong>₹{{ number_format($tax, 2) }}</strong>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between mb-3">
                        <h5>Total:</h5>
                        <h5 class="text-success">₹{{ number_format($total, 2) }}</h5>
                    </div>

                    @if($addresses->count() > 0)
                        <button type="submit" form="checkoutForm" class="btn btn-success btn-lg w-100">
                            <i class="fas fa-check-circle"></i> Place Order
                        </button>
                    @else
                        <button type="button" class="btn btn-secondary btn-lg w-100" disabled>
                            <i class="fas fa-times-circle"></i> Add Address First
                        </button>
                    @endif

                    <div class="text-center small text-muted mt-3">
                        <i class="fas fa-shield-alt"></i> Secure Checkout
                        <br>
                        <i class="fas fa-lock"></i> Your payment information is protected
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Address Modal -->
<div class="modal fade" id="addAddressModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('addresses.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Add New Address</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Address Line 1 *</label>
                        <input type="text" class="form-control" name="address_line1" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Address Line 2</label>
                        <input type="text" class="form-control" name="address_line2">
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">City *</label>
                            <input type="text" class="form-control" name="city" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">State *</label>
                            <input type="text" class="form-control" name="state" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Pincode *</label>
                            <input type="text" class="form-control" name="pincode" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Phone *</label>
                            <input type="text" class="form-control" name="phone" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save Address</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.form-check {
    transition: all 0.3s;
}
.form-check:hover {
    background-color: #f8f9fa;
}
.form-check-input:checked + .form-check-label {
    color: #198754;
}
</style>
@endsection