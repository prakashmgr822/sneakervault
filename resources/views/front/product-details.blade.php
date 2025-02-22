@extends('front.layout')

@section('styles')
    {{--    fontawesome--}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
          integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
          crossorigin="anonymous" referrerpolicy="no-referrer"/>

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
                    <img src="{{ $product->getImage() ?: asset('img/no-img.png') }}" alt="{{ $product->name }}"
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

                    @if($product->description)
                        <p class="mb-1">
                            <strong style="color: #555;">Description:</strong> {{ $product->description }}
                        </p>
                        <hr style="border-top: 1px solid #ccc;">
                    @endif
                    @if($product->specifications)
                        <p class="mb-1"><strong style="color: #555;">Specification:</strong></p>
                        <div class="row my-3">
                            <div class="col-md-12">


                                @if(!$product->specifications)
                                    <div class="alert btn-danger" role="alert">
                                        <i class="fa fa-info-circle mr-2 text-white"></i> No specification
                                    </div>
                                @else
                                    <table class="table table-hover">
                                        <tbody>
                                        @foreach($product->specifications as $index => $specification)
                                            <tr>
                                                @if($specification['name'] == null)

                                                @else
                                                    {{--                                                    <td>{{$index + 1}}</td>--}}
                                                    <td>{{$specification['name'] == null ? 'N/A' : $specification['name']}}</td>
                                                    <td>{{$specification['value'] == null ? 'N/A' : $specification['value']}}</td>
                                                @endif
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                @endif
                            </div>
                        </div>
                @endif

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
