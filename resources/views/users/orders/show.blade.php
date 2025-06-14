@extends('layouts.app')

@section('content')
<div class="container my-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Order Details</h1>
        {{-- The Download Button --}}
        <a href="{{ route('users.orders.download', $order) }}" class="btn btn-primary">
            <i class="bi bi-download me-2"></i>Download Invoice (PDF)
        </a>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <strong>Order #:</strong> {{ $order->order_number }}
                </div>
                <div class="col-md-4">
                    <strong>Order Date:</strong> {{ $order->created_at->format('M d, Y') }}
                </div>
                <div class="col-md-4">
                    <strong>Status:</strong> <span class="badge bg-primary rounded-pill">{{ ucfirst($order->status) }}</span>
                </div>
                <div class="col-md-12 mt-3">
                    <strong>Shipping Address:</strong> {{ $order->shipping_address }}
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header">
            <h4>Order Items</h4>
        </div>
        <div class="card-body">
            @foreach($order->items as $item)
                <div class="row mb-3 border-bottom pb-3">
                    <div class="col-2">
                        @php $imageModel = $item->product->primaryImageModel; @endphp
                        @if ($imageModel->image_path === 'images/default-product.png')
                            <img src="{{ asset($imageModel->image_path) }}" class="img-fluid rounded">
                        @else
                            <img src="{{ Storage::url($imageModel->image_path) }}" class="img-fluid rounded">
                        @endif
                    </div>
                    <div class="col-6">
                        <h5>{{ $item->product->name }}</h5>
                        <p class="mb-1 text-muted">Size: {{ $item->size }}</p>
                        <p class="mb-0 text-muted">Price: €{{ number_format($item->price, 2) }}</p>
                    </div>
                    <div class="col-2 text-center">
                        <p class="mb-1">Quantity</p>
                        <strong>{{ $item->quantity }}</strong>
                    </div>
                    <div class="col-2 text-end">
                         <p class="mb-1">Subtotal</p>
                        <strong>₱{{ number_format($item->price * $item->quantity, 2) }}</strong>
                    </div>
                </div>
            @endforeach
            <div class="row justify-content-end mt-3">
                <div class="col-md-4 text-end">
                    <h4>Total: <strong>₱{{ number_format($order->total_amount, 2) }}</strong></h4>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection