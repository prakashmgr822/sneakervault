@extends('front.layout')

@section('styles')
    <!-- Bootstrap CSS and FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
          integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
          crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .cart-checkout {
            background: #ffffff;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            margin-top: 30px;
        }
        .cart-checkout h3 {
            margin-bottom: 20px;
            border-bottom: 2px solid #f1f1f1;
            padding-bottom: 10px;
        }
        .order-summary p {
            margin-bottom: 8px;
        }
        .form-control:focus {
            border-color: #86b7fe;
            box-shadow: 0 0 8px rgba(13,110,253,0.25);
        }
        .btn-checkout {
            background-color: #28a745;
            border: none;
            font-weight: bold;
            transition: background-color 0.3s;
        }
        .btn-checkout:hover {
            background-color: #218838;
        }
    </style>
@endsection

@section('content')
    <section class="py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="cart-checkout">
                        <h3>Order Summary &amp; Payment</h3>

                        @if(session()->has('error'))
                            <div class="alert alert-danger">{{ session('error') }}</div>
                        @endif

                        <div class="order-summary mb-4">
                            @if($address)
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Delivery Address:</label>
                                    <p class="border rounded p-2">{{ $address }}</p>
                                </div>
                            @else
                                <div class="alert alert-warning">
                                    No delivery address found. Please go back and provide your address.
                                </div>
                            @endif

                            <p>
                                <span>Shipping Cost:</span>
{{--                                <span class="fw-bold float-end">Nrs. {{ $shipping->getValue() ?? 0 }}</span>--}}
                                <span class="fw-bold float-end">Nrs. 0</span>
                            </p>
                            <p>
                                <span>Sub Total:</span>
                                <span class="fw-bold float-end">Nrs. {{ number_format($subTotal, 2) }}</span>
                            </p>
                            <p>
                                <span>Tax:</span>
{{--                                <span class="fw-bold float-end">{{ $tax->getValue() ?? 0 }}</span>--}}
                                <span class="fw-bold float-end">0</span>
                            </p>
                            <p class="border-top pt-2">
                                <span>Total Price:</span>
                                <span class="fw-bold float-end">Nrs. {{ number_format($total, 2) }}</span>
                            </p>
                        </div>

                        <form action="{{ route('payment') }}" method="POST">
                        @csrf
                        <!-- Payment Integration Button (e.g., Khalti) -->
                            <button type="button" id="payment-button" class="btn btn-checkout btn-lg w-100 mb-2">
                                Make Purchase
                            </button>
                            <a href="{{ route('product.home') }}" class="btn btn-outline-secondary btn-lg w-100 mt-3">Back to Shop</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('scripts')
    <script src="https://khalti.s3.ap-south-1.amazonaws.com/KPG/dist/2020.12.17.0.0.0/khalti-checkout.iffe.js"></script>
    <script>
        const totalPrice = parseInt("{{ $total }}");
        var config = {
            "publicKey": "4625bb10a6f5459885d3da153116a69d",
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
                    console.log(payload);
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
