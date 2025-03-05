@extends('front.layout')

@section('styles')
    {{--    fontawesome--}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
          integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
          crossorigin="anonymous" referrerpolicy="no-referrer"/>

    <!-- Aos animation -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('css/style.css')}}">
@endsection

@section('content')
    <section class="bg-light">
        <div class="container">
            <div class="row my-3">
                <!-- Cart Section -->
                <div class="col-lg-9 my-3">
                    @if (session()->has('success'))
                        <div class="alert alert-success mt-3">
                            {{ session()->get('success') }}
                            <button type="button" class="btn-close float-end" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                        </div>
                    @endif

                    @if (session()->has('error'))
                        <div class="alert alert-danger mt-3">
                            {{ session()->get('error') }}
                            <button type="button" class="btn-close float-end" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                        </div>
                    @endif


                    <div class="card border shadow-0">
                        <div class="m-4">
                            <div class="mb-3 d-flex justify-content-between align-items-center">
                                <span class="card-title h4">Your Shopping Cart</span>
                                <a href="{{ route('clear.cart') }}" class="text-danger">Clear All</a>
                            </div>
                            <hr/>

                            @forelse ($cartItems as $item)
                                @php
                                    list($productId, $currentSize) = explode('-', $item->id);
                                    $product = App\Models\Product::find($productId);
                                    $sizes = $product ? $product->product_sizes : [];
                                @endphp

                                <div class="row gy-3">
                                    <div class="col-lg-5">
                                        <div class="d-flex">
                                            <img src="{{ $item->attributes->image ?: asset('img/no-img.png') }}"
                                                 class="border rounded me-3"
                                                 style="width: 96px; height: 96px"/>
                                            <div>
                                                <p class="mb-1 fw-bold">{{ $item->name }}</p>
                                                @if($item->attributes->size) <p class="text-muted mb-0">Size:
                                                    <strong>{{ $item->attributes->size }}</strong></p> @endif
                                                <p class="text-muted mb-0">Nrs. {{ number_format($item->price, 2) }}</p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-2 col-sm-6 col-6">
                                        <div class="input-group" style="min-height: 40px;">
                                            <a href="{{ route('decrease.quantity', $item->id) }}"
                                               class="btn btn-outline-secondary">-</a>
                                            <input type="text" class="form-control text-center"
                                                   value="{{ $item->quantity }}" readonly style="min-height: 40px;">
                                            <a href="{{ route('add.quantity', $item->id) }}"
                                               class="btn btn-outline-secondary">+</a>
                                        </div>
                                    </div>

                                    <div class="col-lg-2">
                                        <p class="h6">Nrs. {{ number_format($item->quantity * $item->price, 2) }}</p>
                                    </div>

                                    <div class="col-lg-3 text-lg-end">
                                        <form action="{{ route('update.size', $item->id) }}" method="POST"
                                              class="update-size-form">
                                            @csrf
                                            <select name="size" class="form-select form-select-sm update-size-dropdown">
                                                @foreach($sizes as $sizeOption)
                                                    <option value="{{ $sizeOption['name'] }}"
                                                        {{ $currentSize == $sizeOption['name'] ? 'selected' : '' }}
                                                        {{ $sizeOption['value'] == 0 ? 'disabled' : '' }}>
                                                        {{ $sizeOption['name'] }}
                                                        ({{ $sizeOption['value'] > 0 ? 'Available: ' . $sizeOption['value'] : 'Out of Stock' }}
                                                        )
                                                    </option>
                                                @endforeach
                                            </select>
                                        </form>
                                        <br>
                                        <a href="{{ route('remove.item', $item->id) }}"
                                           class="btn btn-danger">Remove</a>
                                    </div>
                                </div>
                                <hr>
                            @empty
                                <span class="text-danger">There are no products in the cart</span>
                            @endforelse

                        </div>

                        <div class="border-top pt-4 mx-4 mb-4">
                            <p><i class="fas fa-truck text-muted"></i> Free Delivery within 1-2 weeks</p>
                        </div>
                    </div>
                </div>
                <!-- End Cart Section -->

                <!-- Summary Section -->
                <div class="col-lg-3 my-3">
                    <form action="{{ route('checkout') }}" method="POST">
                        @csrf
                    <div class="card mb-3 border shadow-0">
                        <div class="card-body">
                            {{--                            <form>--}}
                            {{--                                <div class="form-group">--}}
                            {{--                                    <label class="form-label">Have a coupon?</label>--}}
                            {{--                                    <div class="input-group">--}}
                            {{--                                        <input type="text" class="form-control border" placeholder="Coupon code">--}}
                            {{--                                        <button class="btn btn-outline-secondary">Apply</button>--}}
                            {{--                                    </div>--}}
                            {{--                                </div>--}}
                            {{--                            </form>--}}


                                <div class="mb-3">
                                    <label for="address" class="form-label">Delivery Address</label>
                                    <textarea id="address" name="shipping_address" class="form-control" rows="3"
                                              placeholder="Enter your delivery address"  required>{{ old('address', $cartDetails ? $cartDetails->address : '') }}</textarea>
                                    <input type="hidden" name="cart_id" value="{{$cartDetails ? $cartDetails->id : ""}}">
                                </div>
                        </div>
                    </div>

                    <div class="card shadow-0 border mb-3">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <p class="mb-2">Shipping Cost:</p>
                                <p class="mb-2 fw-bold">Nrs. {{ $shipping->getValue() }}</p>
                            </div>
                            <div class="d-flex justify-content-between">
                                <p class="mb-2">Sub Total:</p>
                                <p class="mb-2 fw-bold">Nrs. {{ number_format($subTotal, 2) }}</p>
                            </div>
                            <div class="d-flex justify-content-between">
                                <p class="mb-2">Tax:</p>
                                <p class="mb-2 fw-bold">{{ $tax->getValue() }}</p>
                            </div>
                            <div class="d-flex justify-content-between">
                                <p class="mb-2">Total price:</p>
                                <p class="mb-2 fw-bold">Nrs. {{ number_format($total, 2) }}</p>
                            </div>
                            <div class="mt-3 ">
    @if(!empty($cartItems ))
        <button id="payment-button" class="btn btn-success w-100 shadow-0 mb-2" @if(!$cartDetails) disabled @endif>Proceed to Payment
        </button>
    @else
    <button id="payment-button" class="btn btn-success w-100 shadow-0 mb-2" disabled>Make Purchase
    </button>
                                @endif

    <a href="{{ route('product.home') }}" class="btn btn-outline-secondary w-100">Back to
        shop</a>
</div>

</div>

</div>
</form>
</div>
<!-- End Summary Section -->
</div>
</div>
</section>
@endsection

@section('scripts')
<script>
document.querySelectorAll('.update-size-dropdown').forEach(function (selectElem) {
selectElem.addEventListener('change', function () {
this.form.submit();
});
});
</script>
@endsection

