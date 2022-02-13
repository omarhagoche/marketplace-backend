@extends('operations.client.profile.master')
@push('css_lib')
    <!-- iCheck -->
    <link rel="stylesheet" href="{{asset('plugins/iCheck/flat/blue.css')}}">
    <!-- select2 -->
    <link rel="stylesheet" href="{{asset('plugins/select2/select2.min.css')}}">
    <!-- bootstrap wysihtml5 - text editor -->
    <link rel="stylesheet" href="{{asset('plugins/summernote/summernote-bs4.css')}}">
    {{--dropzone--}}
    <link rel="stylesheet" href="{{asset('plugins/dropzone/bootstrap.min.css')}}">
@endpush
@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">{!! trans('lang.user_profile') !!} <small>/{{$user->name}}</small></h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{url('/dashboard')}}"><i class="fa fa-dashboard"></i> {{trans('lang.dashboard')}}</a></li>
                        <li class="breadcrumb-item active">{{trans('lang.user_profile')}}</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                @include('operations.client.profile.include.about_client')
                <!-- /.col -->
                <div class="col-md-9">
                    @include('flash::message')
                    @include('adminlte-templates::common.errors')
                    <div class="clearfix"></div>
                    <div class="card">
                        <div class="card-header">
                            @include('operations.client.profile.include.links')
                        </div>
                        <div class="card-body">
                            {{-- @include('operations.settings.client.profile.coupon.table') --}}
                            {{-- <div class="form-group row col-md-12 col-sm-12">
                                {!! Form::label('restaurant', trans('lang.restaurant'), ['class' => 'col-6 control-label']) !!}
                                
                                <div class="col-3">
                                    <h3>Code</h3>
                                </div>
                                <div class="col-3">
                                    <h3>Value</h3>
                                </div>
                                <div class="col-3">
                                    <h3>Date</h3>
                                </div>
                                <div class="col-3">
                                    <h3>For</h3>
                                </div>
                                @foreach ($user->coupons() as $coupon)
                                <div class="col-3">
                                    <p>{{$coupon['code']}}</p>
                                </div>
                                <div class="col-3">
                                    <p>{{$coupon['value']}}</p>
                                </div>
                                <div class="col-3">
                                    <p>{{$coupon['date']}}</p>
                                </div>
                                <div class="col-3">
                                    <p>{{$coupon['for']}}</p>
                                </div>
                                @endforeach
                            </div> --}}
                            <table class="table table-hover">
                                <thead>
                                  <tr>
                                    <th scope="col">Code</th>
                                    <th scope="col">Value</th>
                                    <th scope="col">Date</th>
                                    <th scope="col">For</th>
                                  </tr>
                                </thead>
                                <tbody>
                                    @foreach ($user->coupons() as $coupon)
                                    <tr>
                                        <th scope="row">{{$coupon['code']}}</th>
                                        <td>{{$coupon['value']}}</td>
                                        <td>{{$coupon['date']}}</td>
                                        <td>{{$coupon['for']}}</td>
                                    </tr> 
                                    @endforeach
                                 
                                </tbody>
                              </table>

                            <div class="clearfix"></div>
                          </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@push('scripts_lib')
    <!-- iCheck -->
    <script src="{{asset('plugins/iCheck/icheck.min.js')}}"></script>
    <!-- select2 -->
    <script src="{{asset('plugins/select2/select2.min.js')}}"></script>
    <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
    <script src="{{asset('plugins/summernote/summernote-bs4.min.js')}}"></script>
    {{--dropzone--}}
    <script src="{{asset('plugins/dropzone/dropzone.js')}}"></script>
    <script type="text/javascript">
        Dropzone.autoDiscover = false;
        var dropzoneFields = [];
    </script>
@endpush