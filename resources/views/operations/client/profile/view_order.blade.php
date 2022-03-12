@extends('operations.layouts.app')

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
            {{-- @include('operations.client.profile.include.about_client') --}}
            <!-- /.col -->
            <div class="col-md-12">
                @include('flash::message')
                @include('adminlte-templates::common.errors')
                <div class="clearfix"></div>
                <div class="card">
                    <div class="card-header">
                        @include('operations.client.profile.include.links')
                    </div>
                    <div class="card-body">
                        <div class="row">
                          @include('operations.settings.order.show_fields')
                        </div>
                        @include('operations.settings.order.food_orders_table')
                        <div class="row">
                        <div class="col-12 ">
                          <div class="table-responsive table-light">
                            <table class="table">
                              <tbody><tr>
                                <th class="text-left">{{trans('lang.order_subtotal')}}</th>
                                <td>{!! getPrice($subtotal) !!}</td>
                              </tr>
                              <tr>
                                <th class="text-left">{{trans('lang.order_delivery_fee')}}</th>
                                <td>{!! getPrice($order['delivery_fee'])!!}</td>
                              </tr>
                              <tr>
                                <th class="text-left">{{trans('lang.order_tax')}} ({!!$order->tax!!}%) </th>
                                <td>{!! getPrice($taxAmount)!!}</td>
                              </tr>
                              <tr>
                                <th class="text-left">{{trans('lang.delivery_coupon_value')}}</th>
                                <td>{!! getPrice($order['delivery_coupon_value'])!!}</td>
                              </tr>
                              <tr>
                                <th class="text-left">{{trans('lang.restaurant_coupon_value')}}</th>
                                <td>{!! getPrice($order['restaurant_coupon_value'])!!}</td>
                              </tr>
                    
                              <tr>
                                <th class="text-left">{{trans('lang.order_total')}}</th>
                                <td>{!!getPrice($total)!!}</td>
                              </tr>
                              </tbody></table>
                          </div>
                        </div>
                        </div>
                        <div class="clearfix"></div>
                        <div class="row d-print-none">
                          <!-- Back Field -->
                          <div class="form-group col-12 text-right">
                            <a href="{!! route('operations.users.profile.orders',$user->id) !!}" class="btn btn-default"><i class="fa fa-undo"></i> {{trans('lang.back')}}</a>
                          </div>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
  <script type="text/javascript">
    $("#printOrder").on("click",function () {
      window.print();
    });
  </script>
@endpush
