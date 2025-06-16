<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Invoice {{ $order->order_number }}</title>
    <style>
        body { font-family: 'Helvetica', sans-serif; font-size: 14px; }
        .container { width: 100%; margin: 0 auto; }
        .header, .footer { text-align: center; }
        .header h1 { margin: 0; }
        .details { margin-top: 20px; margin-bottom: 20px; }
        .details table { width: 100%; }
        .items-table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        .items-table th, .items-table td { border: 1px solid #ddd; padding: 8px; }
        .items-table th { background-color: #f2f2f2; text-align: left; }
        .text-right { text-align: right; }
        .total { font-weight: bold; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>KickStart</h1>
            <p>Order Invoice</p>
        </div>
        <hr>
        <div class="details">
            <table>
                <tr>
                    <td>
                        <strong>Order #:</strong> {{ $order->order_number }}<br>
                        <strong>Order Date:</strong> {{ $order->created_at->format('M d, Y') }}<br>
                        <strong>Payment Method:</strong> {{ $order->payment_method }}
                    </td>
                    <td class="text-right">
                        <strong>Shipping To:</strong><br>
                        {{ $order->user->name }}<br>
                        {{-- Explode the address string into lines for better formatting --}}
                        {!! nl2br(e($order->shipping_address)) !!}
                    </td>
                </tr>
            </table>
        </div>

        <table class="items-table">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Size</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th class="text-right">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $item)
                    <tr>
                        <td>{{ $item->product->name }}</td>
                        <td>{{ $item->size }}</td>
                        <td>php {{ number_format($item->price, 2) }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td class="text-right">php {{ number_format($item->price * $item->quantity, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="4" class="text-right total">Grand Total</td>
                    <td class="text-right total">php {{ number_format($order->total_amount, 2) }}</td>
                </tr>
            </tfoot>
        </table>

        <div class="footer" style="margin-top: 40px;">
            <p>Thank you for your purchase!</p>
        </div>
    </div>
</body>
</html>