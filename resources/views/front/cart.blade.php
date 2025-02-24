@extends('front.layout')

@section('styles')
    {{--    fontawesome--}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Aos animation -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <link rel="stylesheet" href="{{asset('css/style.css')}}">
@endsection

@section('content')
    <section class="bg-light my-5">
        <div class="container">
            <div class="row">
                <!-- Cart Section -->
                <div class="col-lg-9">
                    @if (session()->has('success'))
                        <div class="alert alert-success">
                            {{ session()->get('success') }}
                        </div>
                    @endif

                    <div class="card border shadow-0">
                        <div class="m-4">
                            <div class="mb-3 d-flex justify-content-between align-items-center">
                                <span class="card-title h4">Your Shopping Cart</span>
                                <a href="{{ route('clear.cart') }}" class="text-danger">Clear All</a>
                            </div>
                            <hr />

                            @foreach ($cartItems as $item)
                                <div class="row gy-3 mb-4">
                                    <div class="col-lg-5">
                                        <div class="d-flex">
                                            <img src="{{ $item->attributes->image }}" class="border rounded me-3"
                                                 style="width: 96px; height: 96px" />
                                            <div>
                                                <p class="mb-1">{{ $item->name }}</p>
                                                <p class="text-muted mb-0">Nrs. {{ number_format($item->price, 2) }}</p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-2 col-sm-6 col-6">
                                        <div class="input-group" style="min-height: 40px;">
                                            <a href="{{ route('decrease.quantity', $item->id) }}" class="btn btn-outline-secondary">-</a>
                                            <input type="text" class="form-control text-center" value="{{ $item->quantity }}" readonly style="min-height: 40px;">
                                            <a href="{{ route('add.quantity', $item->id) }}" class="btn btn-outline-secondary">+</a>
                                        </div>
                                    </div>

                                    <div class="col-lg-2">
                                        <p class="h6">Nrs. {{ number_format($item->quantity * $item->price, 2) }}</p>
                                    </div>

                                    <div class="col-lg-3 text-lg-end">
                                        <a href="{{ route('remove.item', $item->id) }}" class="btn btn-danger">Remove</a>
                                    </div>
                                </div>
                                <hr>
                            @endforeach

                        </div>

                        <div class="border-top pt-4 mx-4 mb-4">
                            <p><i class="fas fa-truck text-muted"></i> Free Delivery within 1-2 weeks</p>
                        </div>
                    </div>
                </div>
                <!-- End Cart Section -->

                <!-- Summary Section -->
                <div class="col-lg-3">
                    <div class="card mb-3 border shadow-0">
                        <div class="card-body">
                            <form>
                                <div class="form-group">
                                    <label class="form-label">Have a coupon?</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control border" placeholder="Coupon code">
                                        <button class="btn btn-outline-secondary">Apply</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="card shadow-0 border">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <p class="mb-2">Total price:</p>
                                <p class="mb-2 fw-bold">Nrs. {{ number_format($total, 2) }}</p>
                            </div>
                            <div class="mt-3">
                                <p class="mb-2">Make Purchase with:</p>
                                <a href="" class="btn btn-success w-100 shadow-0 mb-2">Make Purchase</a>
                                <a href="{{ route('product.home') }}" class="btn btn-outline-secondary w-100">Back to shop</a>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End Summary Section -->
            </div>
        </div>
    </section>
@endsection

