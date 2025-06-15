@extends('layouts.seller') {{-- Or your main seller layout --}}

@section('content')
<div class="container my-5">
    <h1 class="mb-4">My Orders</h1>

    <div class="card shadow-sm">
        <div class="card-body">
            @if($orders->isEmpty())
                <p class="text-center">You have no orders yet.</p>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>Order #</th>
                                <th>Date</th>
                                <th>Customer</th>
                                <th>Total Value (Your Items)</th>
                                <th>Status</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($orders as $order)
                                @php
                                    // Calculate the total value of only the seller's items in this order
                                    $sellerTotal = $order->items->sum(function($item) {
                                        return $item->price * $item->quantity;
                                    });
                                @endphp
                                <tr>
                                    <td><strong>{{ $order->order_number }}</strong></td>
                                    <td>{{ $order->created_at->format('M d, Y') }}</td>
                                    <td>{{ $order->user->name }}</td>
                                    <td>₱{{ number_format($sellerTotal, 2) }}</td>
                                    <td>
                                        <span class="badge bg-primary rounded-pill">{{ ucfirst($order->status) }}</span>
                                    </td>
                                    <td class="text-end">
                                        <a href="{{ route('seller.orders.show', $order) }}" class="btn btn-sm btn-outline-secondary">
                                            Manage Order
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    {{ $orders->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection