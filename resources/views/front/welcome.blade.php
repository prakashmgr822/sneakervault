@extends('front.layout')

@section('styles')
    {{--    fontawesome--}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Aos animation -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <link rel="stylesheet" href="{{asset('css/style.css')}}">

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
                        It is a long established fact that a reader will be distracted by the readable content of a page
                        when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal
                        distribution of letters, as opposed to using 'Content here, content here', making it look like
                        readable English.
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
        <div class="container pt-5 ">
            <div class="row justify-content-md-between">
                <div class="col-md-12">
                    <div class="text-hero-bold text-center">
                        Featured Products
                    </div>
                </div>
                <div class="row pt-4 g-5">
                    @foreach($products as $product)
                        <div class="col-12 col-sm-6 col-md-4 mb-4">
                            <div class="card">
                                @if($product->getImage())
                                    <div>
                                        <img src="{{ $product->getImage() }}" alt="image" class="card-img-top img-fluid">
                                    </div>
                                @endif


                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-6">
                                            <h5 class="card-title">{{$product->name}}</h5>
                                        </div>
                                        <div class="col-6 text-end">
                                            <h5 class="card-title text-label-medium" >Nrs. {{$product->price}}</h5>
                                        </div>
                                    </div>


                                    <h6 class="card-subtitle mb-2 text-muted text-label-regular">{{$product->brand}}</h6>
                                    <div class="row">
                                        <div class="col-8">
                                            <a href="{{route('product-details', ['id' => $product->id])}}" class="btn btn-primary w-100">View Details</a>
                                        </div>
                                        <div class="col-4">
                                            <a href="#" class="btn btn-outline-success w-100 ">Add<i class="fa-solid fa-cart-shopping fa-lg" ></i></a>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
@endsection

@section('script')
@endsection





