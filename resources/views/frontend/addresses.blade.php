@extends('layouts.app')

@section('title', 'My Addresses')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-map-marker-alt"></i> My Addresses</h2>
        <a href="{{ route('addresses.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add New Address
        </a>
    </div>

    @if($addresses->isEmpty())
        <div class="alert alert-info text-center">
            <i class="fas fa-map-marker-alt fa-3x mb-3"></i>
            <h4>No addresses saved</h4>
            <p>Add your delivery addresses for faster checkout!</p>
            <a href="{{ route('addresses.create') }}" class="btn btn-primary">Add Address</a>
        </div>
    @else
        <div class="row">
            @foreach($addresses as $address)
            <div class="col-md-6 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title">Delivery Address</h5>
                        <p class="mb-1">{{ $address->address_line1 }}</p>
                        @if($address->address_line2)
                            <p class="mb-1">{{ $address->address_line2 }}</p>
                        @endif
                        <p class="mb-1">{{ $address->city }}, {{ $address->state }}</p>
                        <p class="mb-1">Pincode: {{ $address->pincode }}</p>
                        <p class="mb-3"><i class="fas fa-phone"></i> {{ $address->phone }}</p>
                        
                        <div class="d-flex gap-2">
                            <a href="{{ route('addresses.edit', $address) }}" class="btn btn-sm btn-warning">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <form action="{{ route('addresses.destroy', $address) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete this address?')">
                                    <i class="fas fa-trash"></i> Delete
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