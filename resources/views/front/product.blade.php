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
@endsection

@section('content')
    <!-- product heading -->
    <section class="product">
        <div class="container ">
            <div class="row mb-5">
                <div class="col-md-12">
                    <div class="text-hero-bold text-center">
                        Our Products
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-8"></div>
                <div class="col-md-4 ">
                    <div class="position-relative w-100">
                        <input type="text" id="search" class="form-control" placeholder="Search sneakers...">

                    </div>
                </div>

            </div>
            <hr>


            <div class="row pt-4 g-5" id="product-list">  <!-- Product Grid -->
                @if(!$products->isEmpty())
                    @foreach($products ?? [] as $product)
                        <div class="col-12 col-sm-6 col-md-4 mb-4 product-item">
                            <div class="card h-100 shadow-sm">
                                {{--                                @if($product->getImage())--}}
                                <div class="position-relative">
                                    <img src="{{ $product->getImage() ?: asset('img/no-img.png') }}" alt="image"
                                         class="card-img-top img-fluid"
                                         style="height: 250px; object-fit: cover;">
                                </div>
                                {{--                                @endif--}}

                                <div class="card-body d-flex flex-column">
                                    <div class="row mb-2">
                                        <div class="col-6">
                                            <h5 class="card-title mb-0">{{$product->name}}</h5>
                                        </div>
                                        <div class="col-6 text-end">
                                            <h5 class="card-title text-label-medium text-primary">
                                                Nrs. {{$product->price}}</h5>
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
                                                <form action="{{ route('addToCart', ['productId' => $product->id]) }}"
                                                      method="POST">
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
                @else
                    <span class="alert alert-danger">Sorry, there are no products at the moment.</span>
                @endif
            </div>
            <!-- Pagination Links -->
            <div class="d-flex justify-content-center mt-4">
                {{ $products->links() }}
            </div>
        </div>
    </section>
@endsection

@section('scripts')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#search').on('keyup', function () {
                let query = $(this).val();

                $.ajax({
                    url: "{{ route('search.products') }}",
                    type: "GET",
                    data: {query: query},
                    success: function (data) {
                        let productList = $('#product-list');
                        productList.html('');  // Clear current products

                        if (data.length > 0) {
                            data.forEach(product => {
                                const addToCartRoute = "{{ route('addToCart', ['productId' => 'id']) }}";
                                let route = addToCartRoute.replace('id', product.id);
                                let productHtml = `
                                <div class="col-12 col-sm-6 col-md-4 mb-4 product-item">
                                    <div class="card h-100 shadow-sm">
                                        <div class="position-relative">
                                            <img src="${product.image_url}" alt="${product.name}" class="card-img-top img-fluid" style="height: 250px; object-fit: cover;">
                                        </div>
                                        <div class="card-body d-flex flex-column">
                                            <div class="row mb-2">
                                                <div class="col-6">
                                                    <h5 class="card-title mb-0">${product.name}</h5>
                                                </div>
                                                <div class="col-6 text-end">
                                                    <h5 class="card-title text-label-medium text-primary">Nrs. ${product.price}</h5>
                                                </div>
                                            </div>
                                            <h6 class="card-subtitle mb-3 text-muted text-label-regular">${product.brand}</h6>
                                <div class="mt-auto">
                                    <div class="row g-4">
                                        <div class="col-8">
                                            <a href="/products/${product.id}"
                                               class="btn btn-primary w-100 d-flex align-items-center justify-content-center"
                                               style="min-height: 50px;">
                                                View Details
                                            </a>
                                        </div>
                                        <div class="col-4">
                                            <form action="${route}" method="POST">
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
                </div>`;
                                productList.append(productHtml);
                            });
                        } else {
                            productList.html('<div class="col-12 text-center text-muted">No products found.</div>');
                        }
                    }
                });
            });
        });
    </script>


@endsection


