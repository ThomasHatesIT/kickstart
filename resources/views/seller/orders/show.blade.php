@extends('layouts.seller') {{-- Or your main seller layout --}}

@section('content')
<div class="container my-5">
    <h1 class="mb-4">Manage Order: {{ $order->order_number }}</h1>
    
    @include('partials._alerts')

    <div class="row">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h4>Items in this Order (Your Products Only)</h4>
                </div>
                <div class="card-body">
                    @foreach($order->items as $item)
                        <div class="row mb-3 border-bottom pb-3">
                            <div class="col-md-8">
                                <h5>{{ $item->product->name }}</h5>
                                <p class="mb-1 text-muted">Size: {{ $item->size }}</p>
                                <p class="mb-0 text-muted">Price: €{{ number_format($item->price, 2) }} x {{ $item->quantity }}</p>
                            </div>
                            <div class="col-md-4 text-end">
                                <h5>€{{ number_format($item->price * $item->quantity, 2) }}</h5>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm mb-4">
                 <div class="card-header">
                    <h4>Customer Details</h4>
                </div>
                <div class="card-body">
                    <strong>Name:</strong> {{ $order->user->name }}<br>
                    <strong>Shipping To:</strong><br>
                    <address class="mt-2">{!! nl2br(e($order->shipping_address)) !!}</address>
                </div>
            </div>
             <div class="card shadow-sm">
                <div class="card-header">
                    <h4>Update Order Status</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('seller.orders.update', $order) }}" method="POST">
                        @csrf
                        @method('PATCH')

                        <div class="mb-3">
                            <label for="status" class="form-label">Order Status</label>
                            <select name="status" id="status" class="form-select">
                                <option value="pending" @selected($order->status === 'pending')>Pending</option>
                                <option value="confirmed" @selected($order->status === 'confirmed')>Confirmed</option>
                                <option value="shipped" @selected($order->status === 'shipped')>Shipped</option>
                                <option value="delivered" @selected($order->status === 'delivered')>Delivered</option>
                            </select>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">Update Status</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection