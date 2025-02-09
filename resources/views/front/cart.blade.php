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
            <div class="row">
                <div class="text-hero-bold text-center">
                    My Cart
                </div>
            </div>
            <div class="row">
                <div class="table-responsive">
                    <table class="table" id="data-table">
                        <thead>
                        <tr class="text-left text-capitalize">
                            <th>S.N.</th>
                            <th>Product</th>
                            <th>Quantity</th>
                            <th>action</th>
                        </tr>
                        </thead>

                    </table>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('scripts')
@endsection
