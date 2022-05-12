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
        <h1 class="m-0 text-dark">{{trans('lang.supermarket')}}<small class="ml-3 mr-3"> {{ $supermarket->name }} </small></h1>
        </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{url('/dashboard')}}"><i class="fa fa-dashboard"></i> {{trans('lang.dashboard')}}</a></li>
          <li class="breadcrumb-item"><a href="{!! route('operations.supermarkets.index') !!}">{{trans('lang.supermarket_plural')}}</a>
          </li>
          <li class="breadcrumb-item active">{{trans('lang.supermarket_products')}}</li>
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
            @include('operations.supermarkets.links',compact('id','supermarket'))
          <div class="card-body">
            @include('operations.supermarkets.products.partials.links')
            @include('operations.supermarkets.products.partials.table')
            <div class="clearfix"></div>
          </div>
        </div>
    </div>
</div>
@include('layouts.media_modal')

@endsection
