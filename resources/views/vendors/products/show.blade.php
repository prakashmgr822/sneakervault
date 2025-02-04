@extends('templates.show')
@push('styles')
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
            <label for=""><span class="show-text">Brand:</span> </label><span> {{ $item->brand??'N/A' }}</span><br>
        </div>
    </div>

    <div class="row my-4">
        <div class="col-md-6">
            <label for=""><span class="show-text">Price: </span></label><span> {{ $item->price??'N/A' }}</span><br>
        </div>
        <div class="col-md-6">
            <label for=""><span class="show-text">Quantity:</span> </label><span> {{ $item->stock_quality??'N/A' }}</span><br>
        </div>
    </div>

    <div class="row my-4">
        <div class="col-md-12">
            <label for=""><span class="show-text">Size:</span> </label><span> {{ $item->size??'N/A' }}</span><br>
        </div>
    </div>

    <div class="row my-4">
        <div class="col-md-12">
            <label for=""><span class="show-text">Description:</span> </label><span> {{ $item->description??'N/A' }}</span><br>
        </div>
    </div>
@endsection
