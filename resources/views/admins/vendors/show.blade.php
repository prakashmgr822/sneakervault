@extends('templates.show')
@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
@endpush
@section('form_content')
    <div class="row my-4">
        <div class="col-md-12">
            @if($item->getImage())
                <img src="{{ $item->getImage() }}" alt="image" width="300px" height="300px" class="mx-auto d-block">
            @endif
        </div>
    </div>

    <div class="row my-4">
        <div class="col-md-6">
            <label for=""><span class="show-text">Name: </span></label><span> {{ $item->name??'N/A' }}</span><br>
        </div>
        <div class="col-md-6">
            <label for=""><span class="show-text">Email:</span> </label><span> {{ $item->email??'N/A' }}</span><br>
        </div>
    </div>

    <div class="row my-4">
        <div class="col-md-6">
            <label for=""><span class="show-text">Phone: </span></label><span> {{ $item->phone??'N/A' }}</span><br>
        </div>
        <div class="col-md-6">
            <label for=""><span class="show-text">Total Sales:</span> </label><span> {{ $item->total_sales??'N/A' }}</span><br>
        </div>
    </div>

    <div class="row my-4">
        <div class="col-md-12">
            <label for=""><span class="show-text">Rating:</span> </label><span> {{ $item->rating??'N/A' }} <i class="fa-solid fa-star" style="color: #FFD43B;"></i></span><br>
        </div>
    </div>

    <div class="row my-4">
        <div class="col-md-12">
            <label for=""><span class="show-text">Description:</span> </label><span> {{ $item->description??'N/A' }}</span><br>
        </div>
    </div>
@endsection
