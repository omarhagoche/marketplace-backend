@extends('operations.layouts.settings.default')
@section('settings_title',trans('lang.user_table'))
@section('settings_content') 
  @include('flash::message')
  <div class="card">
    {{-- <div class="card-header">
      <ul class="nav nav-tabs align-items-end card-header-tabs w-100">
        <li class="nav-item">
          <a class="nav-link active" href="{!! url()->current() !!}"><i class="fa fa-list mr-2"></i>{{trans('lang.user_table')}}</a>
        </li>
        <li class="nav-item">
          <a class="nav-link " href="{!! route('users.create') !!}"><i class="fa fa-plus mr-2"></i>{{trans('lang.user_create')}}</a>
        </li>
        @include('operations.layouts.right_toolbar', compact('dataTable'))
      </ul>
    </div> --}}
    <div class="card-body">
      @include('operations.dashboard.table')
      <div class="clearfix"></div>
    </div>
  </div>

{{-- </div> --}}
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