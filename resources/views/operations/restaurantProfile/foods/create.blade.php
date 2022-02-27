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
        <h1 class="m-0 text-dark">{{trans('lang.restaurant_plural')}}<small class="ml-3 mr-3">|</small><small>{{trans('lang.restaurant_desc')}}</small></h1>
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
        <div class="col-md-3">
            <div class="card ">
              {!! Form::model($restaurant, ['disabled' => 'disabled']) !!}
              <fieldset disabled>
              <div class="row">
                @include('operations.restaurantProfile.profile')
              </div>
              </fieldset>
              {!! Form::close() !!}
            </div>
        </div>
        <div class="col-md-9">
        <div class="card">
          <div class="card-header">
            @include('operations.restaurantProfile.links',compact('id','restaurant'))
          <div class="card-body">
                @include('operations.restaurantProfile.foods.links')
                {!! Form::open(['route' => ['operations.restaurant.foods.store',$id]]) !!}
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
