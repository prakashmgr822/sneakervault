@extends('front.layout')

@section('content')
    <section class="bg-light py-5">
        <div class="container">
            <div class="card shadow">
                <div class="card-body text-center">
                    <h2 class="text-success mb-4">Order Placed Successfully!</h2>
                    <p class="lead">Your order has been confirmed. A confirmation email has been sent to your address.</p>
                    <a href="{{ route('product.home') }}" class="btn btn-primary mt-3">Continue Shopping</a>
                </div>
            </div>
        </div>
    </section>
@endsection
