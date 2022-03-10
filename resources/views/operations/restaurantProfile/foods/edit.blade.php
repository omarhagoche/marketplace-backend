@extends('operations.layouts.app')
@push('css_lib')
<!-- iCheck -->
<link rel="stylesheet" href="{{asset('plugins/iCheck/flat/blue.css')}}">
<!-- select2 -->
<link rel="stylesheet" href="{{asset('plugins/select2/select2.min.css')}}">
<!-- bootstrap wysihtml5 - text editor -->
<link rel="stylesheet" href="{{asset('plugins/summernote/summernote-bs4.css')}}">
{{--dropzone--}}
<link rel="stylesheet" href="{{asset('plugins/dropzone/bootstrap.min.css')}}">
<style>
.avatar {
    vertical-align: middle;
    width: 100px;
    height: 100px;
    border-radius: 50%;
}
</style>

@endpush
@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0 text-dark">{{trans('lang.restaurant')}}<small class="ml-3 mr-3"> {{ $restaurant->name }} </small></h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{url('/dashboard')}}"><i class="fa fa-dashboard"></i> {{trans('lang.dashboard')}}</a></li>
          <li class="breadcrumb-item"><a href="{!! route('restaurants.index') !!}">{{trans('lang.restaurant_plural')}}</a>
          </li>
          <li class="breadcrumb-item active">{{trans('lang.restaurant_edit')}}</li>
        </ol>
      </div><!-- /.col -->
    </div><!-- /.row -->
  </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->
<div class="content">
    <div class="clearfix"></div>
    @include('flash::message')
    <div class="row">
        <div class="col-md-12">
        <div class="card">
          <div class="card-header">
            @include('operations.restaurantProfile.links',compact('id','restaurant'))
          <div class="card-body">
            <div class="clearfix"></div>
                @include('flash::message')
                @include('operations.restaurantProfile.foods.links')
                {!! Form::model($food,['route' => ['operations.restaurant.foods.update',$id,$food->id], 'method' => 'put']) !!}
                <div class="row">
                    @include('operations.restaurantProfile.foods.fields')
                </div>
                {!! Form::close() !!}
            <div class="clearfix"></div>
          </div>
        </div>
    </div>
</div>
@include('layouts.media_modal')

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