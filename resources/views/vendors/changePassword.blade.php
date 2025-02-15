@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Change Password</h1>
@stop

@section('content')
    <section class="content ">
        <div class="container-fluid ">
            <div class="row">
                <!-- left column -->
                <div class="col-md-12 col-offset-6 centered">
                    <!-- general form elements -->
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">{{$title}}</h3>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->
                        <form class="form repeater" id="form" action="{{route('vendor.password.store')}}"
                              method="post" enctype="multipart/form-data">
                            <div class="card-body">
                                @csrf
                                @if ($errors->any())
                                    @foreach ($errors->all() as $error)
                                        <div class="alert alert-danger" role="alert">
                                            <button type="button" class="close" data-dismiss="alert">×</button>
                                            <strong>{{ $error }}</strong>
                                        </div>
                                    @endforeach
                                @endif

                                @if ($message = Session::get('success'))
                                    <div class="alert alert-success alert-block">
                                        <button type="button" class="close" data-dismiss="alert">×</button>
                                        <strong>{{ $message }}</strong>
                                    </div>
                                @endif
                                @if ($message = Session::get('error'))
                                    <div class="alert alert-danger alert-block">
                                        <button type="button" class="close" data-dismiss="alert">×</button>
                                        <strong>{{ $message }}</strong>
                                    </div>
                                @endif

                                <div class="row form-group">
                                    <div class="col-md-6">
                                        <label for="">Old Password *</label>
                                        <div style="position: relative">
                                            <input type="password" class="form-control password-input1" required name="old_password" value="{{ old('old_password') }}" placeholder="Enter Your Old Password">
                                            <span class="far fa-eye togglePassword" id="togglePassword1"
                                              style="position: absolute; top: 13px; right: 13px; cursor: pointer;"></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="row form-group">
                                    <div class="col-md-6">
                                        <label for="">New Password *</label>
                                        <div style="position: relative">
                                            <input type="password" class="form-control password-input2" required name="new_password" value="{{ old('new_password') }}" placeholder="Enter Your New Password">
                                            <span class="far fa-eye" id="togglePassword2"
                                              style="position: absolute; top: 13px; right: 13px; cursor: pointer;"></span>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="">Confirm Password *</label>
                                        <div style="position: relative">
                                            <input type="password" class="form-control password-input3" required name="confirm_password" value="{{ old('confirm_password') }}" placeholder="Re-enter Your New Password">
                                            <span class="far fa-eye" id="togglePassword3"
                                              style="position: absolute; top: 13px; right: 13px; cursor: pointer;"></span>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">Submit</button>
                                <button type="reset" class="btn btn-default float-right">Cancel</button>
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
@stop

@section('css')
    {{--    <link rel="stylesheet" href="/css/admin_custom.css">--}}
@stop

@section('js')
    <script>
        jQuery(document).ready(function () {
            $('#togglePassword1').click(function (e) {
                const type = $('.password-input1').attr('type') === 'password' ? 'text' : 'password';
                $('.password-input1').attr('type', type);
                $(this).toggleClass('fa-eye-slash');
            })

            $('#togglePassword2').click(function (e) {
                const type = $('.password-input2').attr('type') === 'password' ? 'text' : 'password';
                $('.password-input2').attr('type', type);
                $(this).toggleClass('fa-eye-slash');
            })

            $('#togglePassword3').click(function (e) {
                const type = $('.password-input3').attr('type') === 'password' ? 'text' : 'password';
                $('.password-input3').attr('type', type);
                $(this).toggleClass('fa-eye-slash');
            })
        });
    </script>
@stop
