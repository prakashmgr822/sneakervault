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
    <!-- product heading -->
    <section class="product">
        <div class="container ">
            <div class="row">
                <div class="col-md-12">
                    <div class="text-hero-bold text-center">
                        Our Products
                    </div>
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
            <!-- Pagination Links -->
            <div class="d-flex justify-content-center mt-4">
                {{ $products->links() }}
            </div>
        </div>
    </section>
@endsection

@section('script')
@endsection




{{--<html lang="en">--}}
{{--<head>--}}
{{--    <meta charset="UTF-8">--}}
{{--    <meta name="viewport" content="width=device-width, initial-scale=1.0">--}}
{{--    <title>Products</title>--}}
{{--    <!-- Bootstrap -->--}}
{{--    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">--}}
{{--    fontawesome--}}
{{--    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />--}}

{{--    <!-- Aos animation -->--}}
{{--    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">--}}

{{--    <link rel="stylesheet" href="css/style.css">--}}
{{--    <link rel="stylesheet" href="css/product.css">--}}

{{--</head>--}}
{{--<body>--}}

{{--<!-- navbar -->--}}
{{--<header>--}}
{{--    <nav id="desktop-nav">--}}
{{--        <div class="logo">SneakerVault</div>--}}
{{--        <!-- <div> -->--}}
{{--        <ul class="nav-links">--}}
{{--            <li><a href="{{route('home')}}">Home</a></li>--}}
{{--            <li><a href="{{route('product.home')}}">Product</a></li>--}}
{{--            <li><a href="#projects">About</a></li>--}}
{{--        </ul>--}}
{{--        <!-- </div> -->--}}
{{--        <div>--}}
{{--            <i class="fa-solid fa-cart-shopping fa-xl"  style="padding: 20px"></i>--}}
{{--            @if(auth()->user())--}}
{{--                <form id="logout-form" action="{{route('logout')}}" method="POST">--}}
{{--                    {{ csrf_field() }}--}}
{{--                    <input type="submit" class="btn btn-outline-dark" value="Logout">--}}
{{--                </form>--}}
{{--            @else--}}
{{--                <a href="{{route('login')}}">--}}
{{--                    <button type="button" class="btn btn-outline-dark">Login</button>--}}
{{--                </a>--}}
{{--            @endif--}}
{{--        </div>--}}
{{--    </nav>--}}
{{--</header>--}}

{{--<!-- product heading -->--}}
{{--<section class="product">--}}
{{--<div class="container ">--}}
{{--    <div class="row">--}}
{{--        <div class="col-md-12">--}}
{{--            <div class="text-hero-bold text-center">--}}
{{--                Our Products--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--    <div class="row pt-4 g-5">--}}
{{--        @foreach($products as $product)--}}
{{--            <div class="col-12 col-sm-6 col-md-4 mb-4">--}}
{{--                <div class="card">--}}
{{--                    @if($product->getImage())--}}
{{--                        <div>--}}
{{--                            <img src="{{ $product->getImage() }}" alt="image" class="card-img-top img-fluid">--}}
{{--                        </div>--}}
{{--                    @endif--}}


{{--                    <div class="card-body">--}}
{{--                        <div class="row">--}}
{{--                            <div class="col-6">--}}
{{--                                <h5 class="card-title">{{$product->name}}</h5>--}}
{{--                            </div>--}}
{{--                            <div class="col-6 text-end">--}}
{{--                                <h5 class="card-title text-label-medium" >Nrs. {{$product->price}}</h5>--}}
{{--                            </div>--}}
{{--                        </div>--}}


{{--                        <h6 class="card-subtitle mb-2 text-muted text-label-regular">{{$product->brand}}</h6>--}}
{{--                        <div class="row">--}}
{{--                            <div class="col-8">--}}
{{--                                <a href="{{route('product-details', ['id' => $product->id])}}" class="btn btn-primary w-100">View Details</a>--}}
{{--                            </div>--}}
{{--                            <div class="col-4">--}}
{{--                                <a href="#" class="btn btn-outline-success w-100 ">Add<i class="fa-solid fa-cart-shopping fa-lg" ></i></a>--}}
{{--                            </div>--}}
{{--                        </div>--}}

{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        @endforeach--}}
{{--    </div>--}}
{{--    <!-- Pagination Links -->--}}
{{--    <div class="d-flex justify-content-center mt-4">--}}
{{--        {{ $products->links() }}--}}
{{--    </div>--}}
{{--</div>--}}
{{--</section>--}}

{{--<!-- AOS Animate -->--}}
{{--<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>--}}


{{--<!-- bootstrap -->--}}
{{--<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>--}}


{{--</body>--}}
{{--</html>--}}
