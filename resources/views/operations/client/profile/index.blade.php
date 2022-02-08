@extends('operations.client.profile.master')
@section('content')
<section class="content mt-4">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-3">

                <!-- Profile Image -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fa fa-user mr-2"></i> {{trans('lang.user_about_client')}}</h3>
                    </div>
                    <div class="card-body box-profile">
                        <div class="text-center">
                            <img src="{{$user->getFirstMediaUrl('avatar','icon')}}" class="profile-user-img img-fluid img-circle" alt="{{$user->name}}">
                        </div>
                        <h3 class="profile-username text-center">{{$user->name}}</h3>
                        <p class="text-muted text-center">{{implode(', ',$rolesSelected)}}</p>
                        <a class="btn btn-outline-{{setting('theme_color')}} btn-block" href="mailto:{{$user->email}}"><i class="fa fa-envelope mr-2"></i>{{$user->email}}
                        </a>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->

            @if($customFields)
                <!-- About Me Box -->
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fa fa-list mr-2"></i>{{trans('lang.custom_field_plural')}}</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            @foreach($customFieldsValues as $value)
                                <strong>{{trans('lang.user_'.$value->customField->name)}}</strong>
                                <p class="text-muted">
                                    {!! $value->view !!}
                                </p>
                                @if(!$loop->last)
                                    <hr> @endif
                            @endforeach
                        </div>
                        <!-- /.card-body -->
                    </div>
                <!-- /.card -->
                @endif
            </div>
            <!-- /.col -->
            <div class="col-md-9">
                @include('flash::message')
                @include('adminlte-templates::common.errors')
                <div class="clearfix"></div>
                <div class="card">
                    <div class="card-header">
                        <ul class="nav nav-tabs align-items-end card-header-tabs w-100">
                            <li class="nav-item">
                                <a class="nav-link active" href="{!! url()->current() !!}"><i class="fa fa-user mr-2"></i>{{trans('lang.client_info')}}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link " href="{!! url()->current() !!}"><i class="fa fa-star mr-2"></i>{{trans('lang.client_favorites')}}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link " href="{!! url()->current() !!}"><i class="fa fa-first-order mr-2"></i>{{trans('lang.client_orders')}}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link " href="{!! url()->current() !!}"><i class="fa fa-credit-card mr-2"></i>{{trans('lang.client_coupons')}}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link " href="{!! url()->current() !!}"><i class="fa fa-commenting-o  mr-2"></i>{{trans('lang.client_notes')}}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link " href="{!! url()->current() !!}"><i class="fa fa-map-marker  mr-2"></i>{{trans('lang.client_address')}}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link " href="{!! url()->current() !!}"><i class="fa fa-percent  mr-2"></i>{{trans('lang.client_statistics')}}</a>
                            </li>
                           
                            @hasrole('client')
                            <div class="ml-auto d-inline-flex">
                                <li class="nav-item">
                                    <a class="nav-link pt-1" href="{!! route('restaurants.create') !!}"><i class="fa fa-check-o"></i> {{trans('lang.app_setting_become_restaurant_owner')}}</a>
                                </li>
                            </div>
                            @endhasrole
                        </ul>
                    </div>
                    <div class="card-body">
                        {!! Form::model($user, ['route' => ['users.update', $user->id], 'method' => 'patch']) !!}
                        <div class="row">
                            @include('settings.users.fields')
                        </div>
                        {!! Form::close() !!}
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection