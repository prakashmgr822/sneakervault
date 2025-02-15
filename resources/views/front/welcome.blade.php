@extends('front.layout')

@section('styles')
    {{--    fontawesome--}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Aos animation -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <link rel="stylesheet" href="{{asset('css/style.css')}}">
    <link rel="stylesheet" href="{{asset('css/product.css')}}">

@endsection

@section('content')
    <!-- Hero -->
    <section>
        <div class="container">
            <div class="row">
                <div class="col-md-6 order-2 order-md-1" data-aos="fade-up" data-aos-duration="3000">
                    <div class="text-label">
                        All Sneaker in one place
                    </div>
                    <div class="text-hero-bold">
                        Passion for Sneaker
                    </div>
                    <div class="text-hero-regular">
                        Discover the perfect blend of fashion, comfort, and exclusivity at SneakerVault. Whether you're chasing the latest drops, hunting for rare finds, or looking for everyday essentials, we've got you covered. Elevate your sneaker game with our handpicked collection of authentic, high-quality kicks. Your style, your vault—unlock it today!
                    </div>
                    <div class="cta flex-fill">
                        <a href="{{route('product.home')}}" class="btn btn-primary ">Explore Now</a>
                    </div>
                </div>
                <div class="col-md-6 vh-83 order-1 order-md-2" id="hero_img" data-aos="fade-up" data-aos-duration="3000">
                    <img src="img/hero.jpg" class="w-100 img-fluid"
                         style=" object-fit: cover; height: 535.33px; max-height: 100%;" alt="img">
                </div>
            </div>
        </div>
    </section>

    <!-- Featured Products -->
    <section class="product">
        <div class="container pt-4">
            <div class="row mb-5">
                <div class="col-md-12">
                    <div class="text-hero-bold text-center">
                        Featured Products
                    </div>
                </div>
            </div>

            <hr>

            <div class="row py-4 g-5" id="product-list">  <!-- Product Grid -->
                @foreach($products as $product)
                    <div class="col-12 col-sm-6 col-md-4 mb-4 product-item">
                        <div class="card h-100 shadow-sm">
                            @if($product->getImage())
                                <div class="position-relative">
                                    <img src="{{ $product->getImage() }}" alt="image" class="card-img-top img-fluid" style="height: 250px; object-fit: cover;">
                                </div>
                            @endif

                            <div class="card-body d-flex flex-column">
                                <div class="row mb-2">
                                    <div class="col-6">
                                        <h5 class="card-title mb-0">{{$product->name}}</h5>
                                    </div>
                                    <div class="col-6 text-end">
                                        <h5 class="card-title text-label-medium text-primary">Nrs. {{$product->price}}</h5>
                                    </div>
                                </div>

                                <h6 class="card-subtitle mb-3 text-muted text-label-regular">{{$product->brand}}</h6>

                                <div class="mt-auto">
                                    <div class="row g-4">
                                        <div class="col-8">
                                            <a href="{{ route('product-details', ['id' => $product->id]) }}"
                                               class="btn btn-primary w-100 d-flex align-items-center justify-content-center"
                                               style="min-height: 50px;">
                                                View Details
                                            </a>
                                        </div>
                                        <div class="col-4">
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
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <hr>
        </div>

    </section>
@endsection

@section('script')
@endsection





