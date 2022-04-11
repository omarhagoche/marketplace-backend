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
@endpush
@section('content')
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-7">
        <h1 class="m-0 text-dark">{{trans('lang.supermarket_plural')}}<small class="ml-3 mr-3">|</small><small>{{trans('lang.supermarket_managment')}}</small></h1>
      </div>
      <div class="col-sm-5">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{url('operations/')}}"><i class="fa fa-dashboard"></i> {{trans('lang.dashboard')}}</a></li>
          <li class="breadcrumb-item"><a href="{!! route('operations.supermarkets.index') !!}">{{trans('lang.supermarket_plural')}}</a>
          </li>
          <li class="breadcrumb-item active">{{trans('lang.supermarket_table')}}</li>
        </ol>
      </div>
    </div>
  </div>
</div>
<!-- /.content-header -->
<div class="content">
  <div class="clearfix"></div>
  @include('flash::message')
  @include('adminlte-templates::common.errors')
  <div class="clearfix"></div>
  <div class="card">
    <div class="card-header">
      <ul class="nav nav-tabs align-items-end card-header-tabs w-100">
        @can('operations.supermarkets.index')
        <li class="nav-item">
          <a class="nav-link {{ request()->routeIs('operations.supermarkets.index') ? 'active' : '' }}" href="{!! route('operations.supermarkets.index') !!}"><i class="fa fa-list mr-2"></i>{{trans('lang.supermarket_table')}}</a>
        </li>
        @endcan
        @can('operations.supermarkets.create')
        <li class="nav-item">
          <a class="nav-link {{ request()->routeIs('operations.supermarkets.create') ? 'active' : '' }}" href="{!! route('operations.supermarkets.create') !!}"><i class="fa fa-plus mr-2"></i>{{trans('lang.supermarket_create')}}</a>
        </li>
        @endcan
      </ul>
    </div>
    <div class="card-body">
      {!! Form::open(['route' => 'operations.supermarkets.store']) !!}
      <div class="row">
        @include('operations.supermarkets.form_fields.create_fields')
      </div>
      {!! Form::close() !!}
      <div class="clearfix"></div>
    </div>
  </div>
</div>
@include('operations.layouts.media_modal')
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