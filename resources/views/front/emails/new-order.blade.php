<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Order Confirmation</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #f8f9fa; padding: 10px; text-align: center; }
        .content { margin-top: 20px; }
        .order-details { background: #f1f1f1; padding: 10px; border-radius: 4px; }
        a { color: #3490dc; text-decoration: none; }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <h2>Order Placed Successfully!</h2>
    </div>
    <div class="content">
        <p>Hello, {{ $notifiable->name }}!</p>
        <p>Thank you for placing your order. Here are your order details:</p>
        <div class="order-details">
            <ul>
                <li><strong>Order ID:</strong> {{ $order->id }}</li>
                <li><strong>Shipping Address:</strong> {{ $order->shipping_address }}</li>
                <li><strong>Shipping Cost:</strong> Nrs. {{ number_format($order->shipping_cost, 2) }}</li>
                <li><strong>Sub Total:</strong> Nrs. {{ number_format($order->subtotal, 2) }}</li>
                <li><strong>Tax:</strong> Nrs. {{ number_format($order->tax, 2) }}</li>
                <li><strong>Total Amount:</strong> Nrs. {{ number_format($order->total, 2) }}</li>
                <li><strong>Status:</strong> {{ ucfirst($order->status) }}</li>
            </ul>
        </div>
        <p>Thank you for shopping with us!</p>
        <p>{{ config('app.name') }}</p>
    </div>
</div>
</body>
</html>
