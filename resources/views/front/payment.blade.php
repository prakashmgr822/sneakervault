@extends('front.layout')

@section('styles')
    <!-- Bootstrap CSS and FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
          integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
          crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('css/style.css')}}">

    <style>
        .btn-purple {
            background-color: #6f42c1 !important; /* Bootstrap Purple */
            color: white !important;
            border-radius: 5px;
            padding: 10px 20px;
            border: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }
        .btn{
            border-radius: 5px;

        }

        .btn-purple:hover {
            background-color: #5939a3 !important; /* Darker Purple */
        }
    </style>
@endsection

@section('content')
    <section class="bg-light">
        <div class="container mb-3">
            <div class="row">
                <!-- Cart Section -->
                <div class="col-lg-9 mt-3">
                    @if (session()->has('success'))
                        <div class="alert alert-success mt-2">
                            {{ session()->get('success') }}
                            <button type="button" class="btn-close float-end" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                        </div>
                    @endif

                    @if (session()->has('error'))
                        <div class="alert alert-danger mt-2">
                            {{ session()->get('error') }}
                            <button type="button" class="btn-close float-end" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                        </div>
                    @endif
{{--shipping address--}}
                        <div class="card border shadow-0  ">
                            <div class="m-4">
                                <div class="mb-3 d-flex justify-content-between align-items-center">
                                    <span class="card-title h4">Shipping Address</span>
                                </div>
                                <hr/>

                            </div>

                            <div class="cart-body m-4">
                                <div class="mb-2">
                                    <p class="mb-2">{{$user->name}} &nbsp;-&nbsp; <span>{{$user->phone}}</span></p>
                                </div>
                                <div class="mb-2">
                                    <p class="mb-2">Location: {{$orderSummary->shipping_address ?: ""}}</p>
                                </div>
                            </div>

                        </div>
{{--product summary--}}
                        <div class="card border shadow-0 mt-3 ">
                            <div class="m-4">
                                <div class="mb-3 d-flex justify-content-between align-items-center">
                                    <span class="card-title h4">Package</span>
                                </div>
                                <hr/>
                            </div>

                            <div class="cart-body m-4">
                                @if(!empty($cartData))
                                    <div class="row">
                                        @foreach ($cartData as $item)
                                            <div class="col-md-3 col-sm-6 mb-4">
                                                <div class="card border-0 shadow-sm">
                                                    <img src="{{ $item['attributes']['image'] ?: asset("img/no-img.png") }}" class="card-img-top" alt="{{ $item['name'] }}" style="height: 200px; object-fit: cover;">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="card-body text-center">
                                                    <h6 class="card-title">{{ $item['name'] }}</h6>
                                                    <p class="text-muted">Size: {{ $item['attributes']['size'] }}</p>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="card-body text-center">
                                                    <p class="text-danger font-weight-bold">Nrs ${{ $item['price'] }}</p>

                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="card-body text-center">
                                                    <p>Qty: <strong>{{ $item['quantity'] }}</strong></p>

                                                </div>
                                            </div>
                                            <hr>
                                        @endforeach
                                    </div>
                                @else
                                    <p class="text-center">Your cart is empty.</p>
                                @endif

                        </div>

                </div>
                </div>
                <!-- End Cart Section -->

                <!-- Summary Section -->
                <div class="col-lg-3 mt-3">
                    <div class="card shadow-0 border mb-3">
                        <div class="m-4">
                            <div class="mb-3 d-flex justify-content-between align-items-center">
                                <span class="card-title h4">Order Summary and Payment</span>
                            </div>
                            <hr/>
                        </div>
                        <div class="card-body">
                            <div class="d-flex justify-content-between ">
                                <p class="mb-2">Shipping Cost:</p>
                                <p class="mb-2 fw-bold">Nrs. {{ $orderSummary->shipping_cost }}</p>
                            </div>
                            <div class="d-flex justify-content-between">
                                <p class="mb-2">Sub Total:</p>
                                <p class="mb-2 fw-bold">Nrs. {{ $orderSummary->subtotal }}</p>
                            </div>
                            <div class="d-flex justify-content-between">
                                <p class="mb-2">Tax:</p>
                                <p class="mb-2 fw-bold">12%</p>
                            </div>
                            <div class="d-flex justify-content-between">
                                <p class="mb-2">Total price:</p>
                                <p class="mb-2 fw-bold">Nrs. {{ $orderSummary->total }}</p>
                            </div>
                            <div class="mt-3 ">
{{--                                <form action="{{ route('payment') }}" method="POST">--}}
{{--                                @csrf--}}
{{--                                <!-- Payment Integration Button (e.g., Khalti) -->--}}
{{--                                    <p class="mb-2 text-center">Payment Method:</p>--}}
{{--                                    <button type="button" id="payment-button" class="btn btn-purple px-4 btn-lg w-100 mb-2" >--}}
{{--                                        Pay with Khalti--}}
{{--                                    </button>--}}
{{--                                    <a href="" class="btn btn-secondary btn-lg w-100 m">Cash on Delivery</a>--}}
{{--                                    <hr>--}}

{{--                                    <a href="{{ route('cart') }}" class="btn btn-outline-secondary btn-lg w-100">Back to Cart</a>--}}
{{--                                </form>--}}
                                <form id="khalti-form" action="{{ route('process.khalti') }}" method="POST" style="display: none;">
                                    @csrf
                                </form>
                                <form action="{{ route('process.cod') }}" method="POST">
                                    @csrf
                                    <button type="button" id="payment-button" class="btn btn-purple px-4 btn-lg w-100 mb-2">
                                        Pay with Khalti
                                    </button>
                                    <button type="submit" class="btn btn-secondary btn-lg w-100">
                                        Cash on Delivery
                                    </button>
                                </form>
                                <hr>
                                <a href="{{ route('cart') }}" class="btn btn-outline-secondary btn-lg w-100">Back to Cart</a>
                            </div>

                        </div>

                    </div>
                </div>
                <!-- End Summary Section -->
            </div>
        </div>
    </section>
@endsection

@section('scripts')
    <script src="https://khalti.s3.ap-south-1.amazonaws.com/KPG/dist/2020.12.17.0.0.0/khalti-checkout.iffe.js"></script>
    <script>
        const totalPrice = parseInt("{{ $orderSummary->total }}");
        var config = {
            "publicKey": "test_public_key_dc74a653b6de4a039232c708adcdd204",
            "productIdentity": "1234567890",
            "productName": "Dragon",
            "productUrl": "http://gameofthrones.wikia.com/wiki/Dragons",
            "paymentPreference": [
                "KHALTI",
                "EBANKING",
                "MOBILE_BANKING",
                "CONNECT_IPS",
                "SCT",
            ],
            "eventHandler": {
                onSuccess(payload) {
                    fetch('{{ route('process.khalti') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify(payload)
                    })
                        .then(response => {
                            if (response.ok) {
                                window.location.href = '{{ route('order.success') }}';
                            } else {
                                alert('Payment processing failed.');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert('An error occurred. Please try again.');
                        });
                },
                onError(error) {
                    console.log(error);
                },
                onClose() {
                    console.log('widget is closing');
                }
            }
        };

        var checkout = new KhaltiCheckout(config);
        var btn = document.getElementById("payment-button");
        btn.onclick = function () {
            checkout.show({amount: totalPrice * 100});
        }
    </script>
@endsection
