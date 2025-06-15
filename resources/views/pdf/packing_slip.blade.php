<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Packing Slip for Order {{ $order->order_number }}</title>
    <style>
        body { font-family: 'Helvetica', sans-serif; font-size: 14px; }
        .container { width: 100%; margin: 0 auto; }
        .header h1 { margin: 0; }
        .details-table { width: 100%; margin-top: 20px; margin-bottom: 20px; }
        .items-table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        .items-table th, .items-table td { border: 1px solid #ddd; padding: 8px; }
        .items-table th { background-color: #f2f2f2; text-align: left; }
        .text-right { text-align: right; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Packing Slip</h1>
            <p><strong>Order #:</strong> {{ $order->order_number }}</p>
        </div>
        <hr>
        <div class="details">
             <table class="details-table">
                <tr>
                    <td>
                        <strong>Order Date:</strong> {{ $order->created_at->format('M d, Y') }}<br>
                        <strong>Customer:</strong> {{ $order->user->name }}
                    </td>
                    <td class="text-right">
                        <strong>Shipping Address:</strong><br>
                        {!! nl2br(e($order->shipping_address)) !!}
                    </td>
                </tr>
            </table>
        </div>

        <h3>Order Contents</h3>
        <table class="items-table">
            <thead>
                <tr>
                    <th>Product Name</th>
                    <th>SKU / Product ID</th>
                    <th>Size</th>
                    <th class="text-right">Quantity</th>
                </tr>
            </thead>
            <tbody>
                {{-- Remember, the controller already filtered these to be only the seller's items --}}
                @foreach($order->items as $item)
                    <tr>
                        <td>{{ $item->product->name }}</td>
                        <td>{{ $item->product->id }}</td>
                        <td>{{ $item->size }}</td>
                        <td class="text-right">{{ $item->quantity }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="footer" style="margin-top: 40px; text-align: center;">
            <p>Thank you for your business!</p>
        </div>
    </div>
</body>
</html>