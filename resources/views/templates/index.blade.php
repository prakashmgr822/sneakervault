@extends('adminlte::page')


@section('title', $title)

@section('content_header')
    <h1>{{$title}} List</h1>
@stop

@section('css')
    @stack('styles')
@stop

@section('js')

    @stack('scripts')

@stop


@section('content')
    @if (session('success'))
        <div class="alert alert-success alert-block">
            <button type="button" class="close" data-dismiss="alert">×</button>
            {{ session('success') }}
        </div>
    @endif
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">{{$title}}</h3>
                            <div class="float-right">
                                @if(!isset($hideCreate))
                                    <div class="float-right">

                                        <a href="{{route($route.'create')}}"
                                           class="btn btn-dark float-right">
                                            <i class="fa fa-plus"></i>
                                            <span class="kt-hidden-mobile">Add</span>
                                        </a>

                                    </div>
                                @endif

                                @if(isset($showDelete))
                                    <a id="btn-bulk-delete" class="btn btn-danger float-right mr-2">
                                        <i class="fa fa-trash-alt"></i>
                                        <span>Delete Selected</span>
                                    </a>
                                @endif
                            </div>

                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            @yield('index_content')
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
            </div>
            <!-- /.row -->
        </div>
        <!-- /.container-fluid -->
    </section>
@endsection
