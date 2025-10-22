@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <nav id="sidebar" class="col-md-3 col-lg-2 d-md-block bg-light sidebar">
            <div class="position-sticky pt-3">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.dashboard') }}">
                            <i class="fas fa-tachometer-alt"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.categories.index') }}">
                            <i class="fas fa-list"></i> Categories
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.products.index') }}">
                            <i class="fas fa-box"></i> Products
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="{{ route('admin.orders.index') }}">
                            <i class="fas fa-shopping-cart"></i> Orders
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#" id="sidebarLogoutBtn">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </a>
                        <form id="sidebar-logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </li>
                </ul>
            </div>
        </nav>

        <!-- Main content -->
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Order Details #{{ $order->id }}</h1>
                <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to Orders
                </a>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="row">
                <!-- Order Info -->
                <div class="col-md-4">
                    <div class="card mb-4">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">Order Information</h5>
                        </div>
                        <div class="card-body">
                            <p><strong>Order ID:</strong> #{{ $order->id }}</p>
                            <p><strong>Order Date:</strong> {{ $order->created_at->format('d M Y, h:i A') }}</p>
                            <p><strong>Current Status:</strong>
                                <span class="badge 
                                    @if($order->status == 'On Process') bg-warning text-dark
                                    @elseif($order->status == 'Shipped') bg-info
                                    @elseif($order->status == 'Delivered') bg-success
                                    @endif">
                                    {{ $order->status }}
                                </span>
                            </p>
                            <p><strong>Total Amount:</strong> <span class="h5 text-success">₹{{ number_format($order->total_amount, 2) }}</span></p>
                        </div>
                    </div>

                    <!-- Update Status -->
                    <div class="card mb-4">
                        <div class="card-header bg-warning">
                            <h5 class="mb-0">Update Order Status</h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <div class="mb-3">
                                    <label for="status" class="form-label">Change Status</label>
                                    <select class="form-control" id="status" name="status" required>
                                        <option value="On Process" {{ $order->status == 'On Process' ? 'selected' : '' }}>On Process</option>
                                        <option value="Shipped" {{ $order->status == 'Shipped' ? 'selected' : '' }}>Shipped</option>
                                        <option value="Delivered" {{ $order->status == 'Delivered' ? 'selected' : '' }}>Delivered</option>
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-warning w-100">
                                    <i class="fas fa-sync-alt"></i> Update Status
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Customer & Address -->
                <div class="col-md-8">
                    <div class="card mb-4">
                        <div class="card-header bg-info text-white">
                            <h5 class="mb-0">Customer Information</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <h6>Customer Details</h6>
                                    <p><strong>Name:</strong> {{ $order->user->name }}</p>
                                    <p><strong>Email:</strong> {{ $order->user->email }}</p>
                                </div>
                                <div class="col-md-6">
                                    <h6>Delivery Address</h6>
                                    @if($order->address)
                                        <p>
                                            {{ $order->address->address_line1 }}<br>
                                            @if($order->address->address_line2)
                                                {{ $order->address->address_line2 }}<br>
                                            @endif
                                            {{ $order->address->city }}, {{ $order->address->state }}<br>
                                            {{ $order->address->pincode }}<br>
                                            <strong>Phone:</strong> {{ $order->address->phone }}
                                        </p>
                                    @else
                                        <p class="text-muted">No address information available</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Order Items -->
                    <div class="card mb-4">
                        <div class="card-header bg-success text-white">
                            <h5 class="mb-0">Order Items</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Image</th>
                                            <th>Product</th>
                                            <th>Price</th>
                                            <th>Quantity</th>
                                            <th>Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($order->orderItems as $item)
                                        <tr>
                                            <td>
                                                @if($item->product && $item->product->image)
                                                    <img src="{{ asset('storage/' . $item->product->image) }}" 
                                                         alt="{{ $item->product->name }}" 
                                                         style="width: 50px; height: 50px; object-fit: cover;">
                                                @else
                                                    <img src="https://via.placeholder.com/50" alt="No image">
                                                @endif
                                            </td>
                                            <td>{{ $item->product ? $item->product->name : 'Product deleted' }}</td>
                                            <td>₹{{ number_format($item->price, 2) }}</td>
                                            <td>{{ $item->quantity }}</td>
                                            <td>₹{{ number_format($item->price * $item->quantity, 2) }}</td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="5" class="text-center">No items found</td>
                                        </tr>
                                        @endforelse
                                        <tr>
                                            <td colspan="4" class="text-end"><strong>Total:</strong></td>
                                            <td><strong>₹{{ number_format($order->total_amount, 2) }}</strong></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<style>
.sidebar {
    position: fixed;
    top: 56px;
    bottom: 0;
    left: 0;
    z-index: 100;
    padding: 48px 0 0;
    box-shadow: inset -1px 0 0 rgba(0, 0, 0, .1);
}

.sidebar .nav-link {
    font-weight: 500;
    color: #333;
    padding: 10px 15px;
}

.sidebar .nav-link.active {
    color: #007bff;
    background-color: #e7f3ff;
}

.sidebar .nav-link:hover {
    color: #007bff;
}
</style>
@endsection