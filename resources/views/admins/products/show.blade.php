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
        <div class="col-md-4">
            <label for=""><span class="show-text">Vendor: </span></label><span> {{ $item->vendor->name??'N/A' }}</span><br>
        </div>
        <div class="col-md-4">
            <label for=""><span class="show-text">Name: </span></label><span> {{ $item->name??'N/A' }}</span><br>
        </div>
        <div class="col-md-4">
            <label for=""><span class="show-text">Brand:</span> </label><span> {{ $item->brand??'N/A' }}</span><br>
        </div>
    </div>

    <div class="row my-4">
        <div class="col-md-6">
            <label for=""><span class="show-text">Price: </span></label><span> {{ $item->price??'N/A' }}</span><br>
        </div>
        <div class="col-md-6">
            <label for=""><span class="show-text">Quantity:</span>
            </label><span> {{ $item->stock_quality??'N/A' }}</span><br>
        </div>
    </div>

    <div class="row my-4">
        <div class="col-md-12">
            <label for=""><span class="show-text">Size:</span> </label><span> {{ $item->size??'N/A' }}</span><br>
        </div>
    </div>

    <div class="row my-4">
        <div class="col-md-12">
            <label for=""><span class="show-text">Description:</span>
            </label><span> {{ $item->description??'N/A' }}</span><br>
        </div>
    </div>

    <h5><strong>Specifications</strong></h5>
    <div class="row my-3">
        <div class="col-md-12">


            @if($item->specification)
                <div class="alert btn-danger" role="alert">
                    <i class="fa fa-info-circle mr-2 text-white"></i> No specification
                </div>
            @else
                <table class="table table-hover">
                    <thead class="thead-light">
                    <tr>
                        <th scope="col">SN</th>
                        <th scope="col">Name</th>
                        <th scope="col">Value</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($item->specifications as $index => $specification)
                        <tr>
                            @if($specification['name'] == null)

                            @else
                                <td>{{$index + 1}}</td>
                                <td>{{$specification['name'] == null ? 'N/A' : $specification['name']}}</td>
                                <td>{{$specification['value'] == null ? 'N/A' : $specification['value']}}</td>
                            @endif
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
@endsection
