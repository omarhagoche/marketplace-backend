@extends('operations.client.profile.master')
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
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">{!! trans('lang.user_profile') !!} <small>/{{$user->name}}</small></h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{url('/dashboard')}}"><i class="fa fa-dashboard"></i> {{trans('lang.dashboard')}}</a></li>
                        <li class="breadcrumb-item active">{{trans('lang.user_profile')}}</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                @include('operations.client.profile.include.about_client')
                <!-- /.col -->
                <div class="col-md-9">
                    @include('flash::message')
                    @include('adminlte-templates::common.errors')
                    <div class="clearfix"></div>
                    <div class="card">
                        <div class="card-header">
                            @include('operations.client.profile.include.links')
                        </div>
                        <div class="card-body">
                            {{-- @include('operations.settings.note.table') --}}
                            {{-- <canvas id="myChart" style="width:100%;max-width:600px"></canvas> --}}
                            <div class="content">
                                <!-- Small boxes (Stat box) -->
                                <div class="row">
                                    <div class="col-lg-3 col-6">
                                        <!-- small box -->
                                        <div class="small-box bg-warning">
                                            <div class="inner">
                                                <h3>{{$data['orderCount']}}</h3>
                        
                                                <p>{{trans('lang.delivery_success')}}</p>
                                            </div>
                                            <div class="icon">
                                                <i class="fa fa-truck"></i>
                                            </div>
                                            
                                        </div>
                                    </div>
                                    <!-- ./col -->
                                    <div class="col-lg-3 col-6">
                                        <!-- small box -->
                                        <div class="small-box bg-success">
                                            <div class="inner">
                                             
                                                    <h3>{{$data['total_money']}}</h3>
                        
                                                <p>{{trans('lang.total_money_for_user')}} ({{setting('default_currency')}})</p>
                                            </div>
                                            <div class="icon">
                                                <i class="fa fa-cc"></i>
                                            </div>
                                            
                                        </div>
                                    </div>
                                    <!-- ./col -->
                                    <div class="col-lg-3 col-6">
                                        <!-- small box -->
                                        <div class="small-box bg-primary">
                                            <div class="inner">
                                                <h3>{{$data['visited']}}</h3>
                                                <p>{{trans('lang.visited_restaurants')}}</p>
                                            </div>
                                            <div class="icon">
                                                <i class="fa fa-cutlery"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- ./col -->
                                    <div class="col-lg-3 col-6">
                                        <!-- small box -->
                                        <div class="small-box bg-danger">
                                            <div class="inner">
                                                <h3>{{$data['orderCanceled']}}</h3>
                        
                                                <p>{{trans('lang.cancel_order')}}</p>
                                            </div>
                                            <div class="icon">
                                                <i class="fa fa-close"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- ./col -->
                        
                                </div>
                                <!-- /.row -->
                            </div>



                            <div class="clearfix"></div>
                          </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@push('scripts_lib')
<script src="{{asset('plugins/chart.js/Chart.min.js')}}"></script>

<script>
    var xValues = [ "Spain", "USA", "Argentina","libya"];
    var yValues = [55, 49, 44, 24, 20,15];
    var barColors = [
      "#b91d47",
      "#00aba9",
      "#2b5797",
      "#e8c3b9",
      "#1e7145"
    ];
    
    new Chart("myChart", {
      type: "pie",
      data: {
        labels: xValues,
        datasets: [{
          backgroundColor: barColors,
          data: yValues
        }]
      },
      options: {
        title: {
          display: true,
          text: "World Wide Wine Production 2018"
        }
      }
    });
    </script>
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