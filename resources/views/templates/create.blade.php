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
                              method="post" enctype="multipart/form-data" onsubmit="return validateForm()">
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
    <script>
        jQuery(document).ready(function () {
            $('#summernote').summernote({
                height: 400,
                callbacks: {
                    onImageUpload: function (files) {
                        for (let i = 0; i < files.length; i++) {
                            $.upload(files[i]);
                        }
                    },
                    onMediaDelete: function (target) {
                        const src = $(target[0]).attr('src');
                        const imageId = $(target[0]).attr('data-id');

                        deleteFile(imageId);
                    }
                },
            });
            $('#summernote1').summernote({
                height: 400,
                callbacks: {
                    onImageUpload: function (files) {
                        for (let i = 0; i < files.length; i++) {
                            $.upload(files[i]);
                        }
                    },
                    onMediaDelete: function (target) {
                        const src = $(target[0]).attr('src');
                        const imageId = $(target[0]).attr('data-id');

                        deleteFile(imageId);
                    }
                },
            });

            $.upload = function (file) {
                let out = new FormData();
                out.append("_token", "{{ csrf_token() }}")
                out.append('file', file, file.name);

                $.ajax({
                    headers: {
                        "X-CSRFToken": '{{csrf_field()}}'
                    },
                    method: 'POST',
                    url: '{{route('uploader.store')}}',
                    contentType: false,
                    cache: false,
                    processData: false,
                    data: out,
                    success: function (data) {
                        if (data['status']) {
                            var url = data['data']['url'];
                            var id = data['data']['id'];

                            $('#summernote').summernote('insertImage', url, function ($image) {
                                $image.attr('data-id', id);
                            });
                        } else {
                            showFailedMessage()
                        }
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        console.error(textStatus + " " + errorThrown);
                        showFailedMessage()
                    }
                });
            }

            function deleteFile(id) {
                var url = '{{ route('uploader.destroy', ":id") }}';
                url = url.replace(':id', id);

                $.ajax({
                    method: 'POST',
                    dataType: 'JSON',
                    url: url,
                    data: {
                        'id': id,
                        '_token': '{{ csrf_token() }}',
                        '_method': 'DELETE',
                    },
                    success: function (data) {

                    },

                    error: function (jqXHR, textStatus, errorThrown) {
                        showFailedMessage()
                    }
                });
            }
        });
    </script>
    @stack('scripts')
@endsection
