@extends('operations.layouts.app')
@push('css_lib')
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
@include('operations.supermarkets.breadcrumbs',['path_name'=>'lang.product_create'])

<!-- /.content-header -->
<div class="content">
    <div class="clearfix"></div>
    
        <div class="col-md-12">
        <div class="card">
          <div class="card-header">       
            @include('operations.supermarkets.links',compact('id','supermarket'))
          <div class="card-body">
            <div class="clearfix"></div>
                @include('flash::message')
                @include('adminlte-templates::common.errors')
                @include('operations.supermarkets.products.partials.links')
                {!! Form::open(['route' => ['operations.supermarkets.products.store',$id]]) !!}
                <div class="row">
                    @include('operations.supermarkets.products.form_fields.create_fields')
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
<script src="{{asset('plugins/dropzone/dropzone.js')}}"></script>
<script type="text/javascript">
    Dropzone.autoDiscover = false;
    var dropzoneFields = [];
</script>
@endpush