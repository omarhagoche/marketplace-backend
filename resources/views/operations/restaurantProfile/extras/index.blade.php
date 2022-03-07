@extends('operations.layouts.app')

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0 text-dark">{{trans('lang.extra_plural')}}<small class="ml-3 mr-3">|</small><small>{{trans('lang.extra_desc')}}</small></h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{url('/dashboard')}}"><i class="fa fa-dashboard"></i> {{trans('lang.dashboard')}}</a></li>
          <li class="breadcrumb-item"><a href="{!! route('extras.index') !!}">{{trans('lang.extra_plural')}}</a>
          </li>
          <li class="breadcrumb-item active">{{trans('lang.extra_table')}}</li>
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
            <ul class="nav nav-tabs align-items-end card-header-tabs w-100">
            @include('operations.restaurantProfile.extras.links')
            @include('layouts.right_toolbar', compact('dataTable'))
            </ul>
            <br>
            @include('operations.restaurantProfile.extras.table')
            <div class="clearfix"></div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

