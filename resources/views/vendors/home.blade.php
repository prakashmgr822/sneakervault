@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Vendor Dashboard</h1>
@stop


@section('content')
    <div class="row">
        <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-gradient-danger">
                <div class="inner">
                    <h3>{{$products}}</h3>
                    <p>Total Products</p>
                </div>
                <div class="icon">
                    <i class="fas fa-shoe-prints"></i>
                </div>
                <a href="{{route('products.index')}}" class="small-box-footer">More info <i
                        class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>

    </div>
@endsection

@section('css')

@stop

@section('js')

@stop
