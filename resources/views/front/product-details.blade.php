@extends('front.layout')

@section('styles')
    {{--    fontawesome--}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Aos animation -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <link rel="stylesheet" href="{{asset('css/style.css')}}">
{{--    <link rel="stylesheet" href="{{asset('css/product.css')}}">--}}
    <link rel="stylesheet" href="{{asset('css/product-details.css')}}">
@endsection

@section('content')
    <section>
        <div class="container pb-5">
            <div class="row mt-2">
                <div class="col-md-12">
                    <div class="text-hero-bold text-center">
                        Product Details
                    </div>
                </div>
            </div>
            <div class="row g-5 mt-2">
                <div class="col-md-6">
                    <img src="{{$product->getImage()}}" alt="image" class="card-img-top img-fluid">
                </div>
                <div class="col-md-6 row d-flex align-content-between">
                    <div class="text-heading-semi-bold">
                        {{$product->name}}
                    </div>
                    <div class="text-label-regular">
                        Brand: {{$product->brand}}
                    </div>

                    <hr>

                    <div class="text-label-regular">
                        Product Description: {{$product->description}}
                    </div>
                    <hr>
                    <div class="text-heading-semi-bold">
                        Nrs.{{$product->price}} <span >/-</span>
                    </div>
                    <div class="align-self-end">
                        <a href="{{route('cart')}}" class="btn btn-outline-success">Add to<i class="fa-solid fa-cart-shopping fa-lg" ></i></a>
                    </div>

                </div>
            </div>
        </div>
    </section>
@endsection

@section('script')
@endsection
