@extends('layouts.seller') {{-- Your seller layout --}}

@section('content')
<div class="container my-5">
    <h1 class="mb-4">Sales History</h1>

    <div class="card shadow-sm">
        <div class="card-body">
            @if($sales->isEmpty())
                <p class="text-center">You have no sales history yet.</p>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Order #</th>
                                <th>Product</th>
                                <th>Qty</th>
                                <th>Price per Item</th>
                                <th>Total Value</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($sales as $sale)
                                <tr>
                                    <td>{{ $sale->created_at->format('M d, Y') }}</td>
                                    <td>
                                        <a href="{{ route('seller.orders.show', $sale->order) }}">
                                            {{ $sale->order->order_number }}
                                        </a>
                                    </td>
                                    <td>{{ $sale->product->name }}</td>
                                    <td>{{ $sale->quantity }}</td>
                                    <td>€{{ number_format($sale->price, 2) }}</td>
                                    <td><strong>€{{ number_format($sale->price * $sale->quantity, 2) }}</strong></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    {{ $sales->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection