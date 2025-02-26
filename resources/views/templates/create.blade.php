@extends('adminlte::page')

@section('title', 'Add '.$title)

@section('content_header')
    <h1>Add {{$title}}</h1>
@stop

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/plugins/summernote/summernote-bs4.min.css')}}">
    {{--    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">--}}
    @stack('styles')
@stop

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <!-- left column -->
                <div class="col">
                    <!-- general form elements -->
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">{{$title}}</h3>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->
                        <form class="form repeater" id="form" name="myForm" action="{{route($route.'store')}}"
                              method="post" enctype="multipart/form-data">
                            <div class="card-body">
                                @csrf
                                @if ($errors->any())
                                    @foreach ($errors->all() as $error)
                                        <div class="alert alert-danger" role="alert">
                                            {{$error}}
                                        </div>
                                    @endforeach
                                @endif
                                <input name="add_more" type="hidden" id="add-more" value="{{false}}">
                                @yield('form_content')

                            </div>
                            <div class="card-footer">
                                <button type="submit" id="button_submit" class="button_submit btn btn-primary"
                                        name="action" value="submit">Submit
                                </button>
                                {{--                            @if(isset($addMoreButton))--}}
                                {{--                            <button type="submit" id="button_submit_add" class="button_submit btn btn-primary"--}}
                                {{--                                    name="action" value="add">--}}
                                {{--                                Submit & Add new--}}
                                {{--                            </button>--}}
                                {{--                            @endif--}}
                                <a href="javascript:history.back();" class="btn btn-default float-right">Cancel</a>
                            </div>
                        </form>
                    </div>
                    <!-- /.card -->

                </div>
                <!--/.col (left) -->
            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </section>
@endsection
@section('js')
    {{--    <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>--}}
    <script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.19.0/jquery.validate.min.js"></script>
    <script>
        let specifications = [];
        let productSizes = [];

        jQuery(document).ready(function () {
            $('#button_submit').click(
                function (e) {

                    let form = $('#form');
                    if (!$('#form').valid()) {
                        return;
                    }
                    let names = [...$('.specficationName')];
                    let values = [...$('.specficationValue')];

                    let sizes = [...$('.sizes')];
                    let quantities = [...$('.quantities')];

                    names.forEach(function (name, obj) {
                        let keyValue = {
                            name: name.value,
                            value: values[obj].value
                        }

                        specifications.push(keyValue)

                    });

                    sizes.forEach(function (name, obj) {
                        let keyValue = {
                            name: name.value,
                            value: quantities[obj].value
                        }

                        productSizes.push(keyValue)

                    });

                    form.append(`
                        <input name="specifications" type="hidden" value='${JSON.stringify(specifications)}'>
                        <input name="product_sizes" type="hidden" value='${JSON.stringify(productSizes)}'>
                    `);

                    $('#form').submit();
                });
        });
    </script>
    @stack('scripts')
@endsection
