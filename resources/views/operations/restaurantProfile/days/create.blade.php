@extends('operations.layouts.app')

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
            @include('operations.restaurantProfile.links',compact('restaurant'))
          <div class="card-body">
            {!! Form::open( ['route' => ['operations.restaurant_profile.days.store', $restaurant->id]]) !!}
            @include('operations.restaurantProfile.days.fields')

            {!! Form::close() !!}

            <div class="clearfix"></div>
          </div>
        </div>
    </div>
</div>
{{-- @include('layouts.media_modal') --}}

@endsection
