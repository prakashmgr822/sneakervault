@extends('front.layout')

@section('styles')
    {{--    fontawesome--}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Aos animation -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <link rel="stylesheet" href="{{asset('css/style.css')}}">
    <link rel="stylesheet" href="{{asset('css/product.css')}}">
    <link rel="stylesheet" href="{{asset('css/product-details.css')}}">

@endsection

@section('content')
    <section>
            <div class="container pb-5">
                <!-- Section Title -->
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="text-hero-bold text-center">
                            Product Details
                        </div>
                    </div>
                </div>

                <!-- Product Details -->
                <div class="row g-5 align-items-center">
                    <!-- Product Image -->
                    <div class="col-md-6">
                        <img src="{{ $product->getImage() }}" alt="{{ $product->name }}"
                             class="img-fluid rounded shadow-sm"
                             style="border: 1px solid #ddd;">
                    </div>


                    <!-- Product Info -->
                    <div class="col-md-6">
                        <h3 class="mb-3" style="color: #222;">{{ $product->name }}</h3>
                        <p class="mb-1">
                            <strong style="color: #555;">Brand:</strong> {{ $product->brand }}
                        </p>
                        <h4 class="mb-3" style="color: #e74c3c;">
                            Nrs. {{ $product->price }} <small>/-</small>
                        </h4>
                        <hr style="border-top: 1px solid #ccc;">
                        <p class="mb-1">
                            <strong style="color: #555;">Description:</strong> {{ $product->description }}
                        </p>

                        <!-- Add to Cart Button -->
                        <form action="{{ route('addToCart', ['productId' => $product->id]) }}" method="POST">
                            @csrf
                            <button type="submit"
                                    class="btn btn-success w-100 d-flex align-items-center justify-content-center"
                                    style="min-height: 50px;">
                                <i class="fa-solid fa-cart-shopping me-2"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
    </section>
@endsection

@section('script')

@endsection
