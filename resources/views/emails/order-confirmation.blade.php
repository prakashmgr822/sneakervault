@component('mail::message')
    # Order Confirmation

    Your order #{{ $order->id }} has been placed successfully!

    @component('mail::panel')
        **Shipping Address:**
        {{ $order->shipping_address }}
        **Status:** {{ ucfirst($order->status) }}
    @endcomponent

    **Order Details:**

    @foreach ($order->products as $product)
        - **Product:** {{ $product->name }}
        **Size:** {{ $product->pivot->size }}
        **Quantity:** {{ $product->pivot->quantity }}
        **Price:** NPR {{ $product->price }}
    @endforeach

    ---
    **Subtotal:** NPR {{ $order->subtotal }}
    **Tax (12%):** NPR {{ $order->tax }}
    **Shipping Cost:** NPR {{ $order->shipping_cost }}
    **Total:** NPR {{ $order->total }}

    Thanks for shopping with us,
    {{ config('app.name') }}
@endcomponent
