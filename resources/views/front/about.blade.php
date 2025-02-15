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
                        Who Are We?
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="text-hero-regular">
                        Welcome to SneakerVault, your ultimate destination for exclusive, high-quality sneakers. Whether you're a sneakerhead or a first-time buyer, we’ve got something for everyone. We believe sneakers are more than just footwear—they are an expression of style, personality, and culture. That’s why we’ve curated a collection of the latest and most iconic sneakers from renowned brands, limited editions, and unique designs.
                    </div>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-md-12">
                    <div class="text-hero-bold">
                        Our Mission
                    </div>
                </div>
                <div class="text-hero-regular col-md-12">
                    At SneakerVault, we strive to deliver the best sneakers and a seamless shopping experience to all our customers. Our mission is to provide high-quality footwear that fits every style, budget, and need. We offer a diverse range of sneakers, from classic models to the newest releases. We’re committed to helping you find the perfect pair that not only looks great but feels great too.
                </div>


            </div>

        </div>
    </section>
@endsection

@section('script')
@endsection



