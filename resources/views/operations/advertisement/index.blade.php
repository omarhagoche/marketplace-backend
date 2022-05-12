@extends('operations.layouts.app')

@section('content')
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-7">
        <h1 class="m-0 text-dark">{{trans('lang.advertisement_plural')}}<small class="ml-3 mr-3">|</small><small>{{trans('lang.advertisement_managment')}}</small></h1>
      </div>
      <div class="col-sm-5">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{url('operations/')}}"><i class="fa fa-dashboard"></i> {{trans('lang.dashboard')}}</a></li>
          <li class="breadcrumb-item"><a href="{!! route('operations.advertisement.index') !!}">{{trans('lang.advertisement_plural')}}</a>
          </li>
          <li class="breadcrumb-item active">{{trans('lang.advertisement_table')}}</li>
        </ol>
      </div>
    </div>
  </div>
</div>

<div class="content">
  <div class="clearfix"></div>
  @include('flash::message')
  <div class="card">
    <div class="card-header">
      <ul class="nav nav-tabs align-items-end card-header-tabs w-100">
        <li class="nav-item">
          <a class="nav-link active" href="{!! url()->current() !!}"><i class="fa fa-list mr-2"></i>{{trans('lang.advertisement_table')}}</a>
        </li>
        @can('operations.advertisement.create')
        <li class="nav-item">
          <a class="nav-link" href="{!! route('operations.advertisement.create') !!}"><i class="fa fa-plus mr-2"></i>{{trans('lang.advertisement_create')}}</a>
        </li>
        @endcan
        @include('operations.layouts.right_toolbar', compact('dataTable'))
      </ul>
    </div>
    <div class="card-body">
      @include('operations.advertisement.table')
      <div class="clearfix"></div>
    </div>
  </div>
</div>
@endsection

