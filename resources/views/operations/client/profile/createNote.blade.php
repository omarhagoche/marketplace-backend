@extends('operations.client.profile.master')
@section('content')
    <!-- Content Header (Page header) -->
       @include('operations.client.profile.include.content-header')
    <!-- /.content-header -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    @include('flash::message')
                    @include('adminlte-templates::common.errors')
                    <div class="clearfix"></div>
                    <div class="card">
                        <div class="card-header">
                            @include('operations.client.profile.include.links')
                        </div>
                        <div class="card-body">
                            {!! Form::open( ['route' => ['operations.users.profile.storeNote', $user->id]]) !!}
                            <div class="row">
                                @include("operations.settings.note.fields",compact('user'))
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