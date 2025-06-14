@extends('layouts.app')

@section('content')
<div class="container my-5">
    <h1 class="mb-4">My Orders</h1>

    <div class="card shadow-sm">
        <div class="card-body">
            @if($orders->isEmpty())
                <p class="text-center">You have not placed any orders yet.</p>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>Order #</th>
                                <th>Date</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th>Items</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($orders as $order)
                                <tr>
                                    <td><strong>{{ $order->order_number }}</strong></td>
                                    <td>{{ $order->created_at->format('M d, Y') }}</td>
                                    <td>€{{ number_format($order->total_amount, 2) }}</td>
                                    <td>
                                        <span class="badge bg-primary rounded-pill">{{ ucfirst($order->status) }}</span>
                                    </td>
                                    <td>{{ $order->items_count }}</td>
                                    <td class="text-end">
                                        <a href="{{ route('users.orders.show', $order) }}" class="btn btn-sm btn-outline-secondary">
                                            View Details
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