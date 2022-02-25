@extends('layouts.app')
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
  @include('adminlte-templates::common.errors')
  <div class="clearfix"></div>
    <div class="row">
        <div class="col-md-3">
            <div class="card ">
                <div class="card-header" style="align-self: center;"> 
                    <div class="col-12" style="align-self: center;"> 
                      <img src="{{ $restaurant->media->first()?$restaurant->media->first()->getUrl():''}}" alt="Avatar" class="avatar">
                    </div>
                    <div class="col-12">
                        
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-9">
            <div class="card">
                
                @include('operations.restaurantProfile.links',compact('id','restaurant'))

                <div class="card-body">
                  {{-- <div class="row">
                    {!! Form::open() !!}
                    {!! Form::select('user_id', $users, 'name') !!}
                    {!! Form::button('<i class="fa fa-trash"></i>', [
                      'data-toggle' => 'tooltip',
                      'data-placement' => 'bottom',
                      'title' => trans('lang.user_delete'),
                      'type' => 'submit',
                      'class' => 'btn btn-link text-danger',
                      'onclick' => "swal({title: ".trans('lang.error').", confirmButtonText: ".trans('lang.ok').",
                                              text: data.message,type: 'error', confirmButtonClass: 'btn-danger'});"
                      ]) !!}
                    {!! Form::close() !!}
                  </div> --}}
                  {!! Form::open(['route' => ['operations.restaurant_profile.users.store',$id,$userId]]) !!}
                  <div class="row">
                    @include('settings.users.fields',compact('user'))
                  </div>
                  {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
</div> 
@include('layouts.media_modal')
@prepend('scripts')
<script type="text/javascript">
    var user_avatar = '';
    @if(isset($user) && $user->hasMedia('avatar'))
        user_avatar = {
        name: "{!! $user->getFirstMedia('avatar')->name !!}",
        size: "{!! $user->getFirstMedia('avatar')->size !!}",
        type: "{!! $user->getFirstMedia('avatar')->mime_type !!}",
        collection_name: "{!! $user->getFirstMedia('avatar')->collection_name !!}"
    };
            @endif
    var dz_user_avatar = $(".dropzone.avatar").dropzone({
            url: "{!!url('uploads/store')!!}",
            addRemoveLinks: true,
            maxFiles: 1,
            init: function () {
                @if(isset($user) && $user->hasMedia('avatar'))
                dzInit(this, user_avatar, '{!! url($user->getFirstMediaUrl('avatar','thumb')) !!}')
                @endif
            },
            accept: function (file, done) {
                dzAccept(file, done, this.element, "{!!config('medialibrary.icons_folder')!!}");
            },
            sending: function (file, xhr, formData) {
                dzSending(this, file, formData, '{!! csrf_token() !!}');
            },
            maxfilesexceeded: function (file) {
                dz_user_avatar[0].mockFile = '';
                dzMaxfile(this, file);
            },
            complete: function (file) {
                dzComplete(this, file, user_avatar, dz_user_avatar[0].mockFile);
                dz_user_avatar[0].mockFile = file;
            },
            removedfile: function (file) {
                dzRemoveFile(
                    file, user_avatar, '{!! url("users/remove-media") !!}',
                    'avatar', '{!! isset($user) ? $user->id : 0 !!}', '{!! url("uplaods/clear") !!}', '{!! csrf_token() !!}'
                );
            }
        });
    dz_user_avatar[0].mockFile = user_avatar;
    dropzoneFields['avatar'] = dz_user_avatar;
</script>
@endprepend
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
