<!-- Id Field -->
<div class="form-group row col-md-6 col-sm-12">
    {!! Form::label('id', trans('lang.order_id'), ['class' => 'col-6 control-label']) !!}
  <div class="col-4">
    <p>#{!! $order->id !!}</p>
  </div>

    {!! Form::label('order_client', trans('lang.order_client'), ['class' => 'col-6 control-label']) !!}
  <div class="col-4">
    <p>{!! $order->user->name ?? $order->unregistered_customer->name !!}</p>
  </div>

  
    {!! Form::label('Add by restaurant','Add by restaurant', ['class' => 'col-6 control-label']) !!}
    <div class="col-4">
      @if($order->user_id)
        <p><span class='badge badge-danger'>{{trans('lang.no')}}</span></p>
      @else
        <p><span class='badge badge-success'>{{trans('lang.yes')}}</span></p>
      @endif
    </div>
  
    {!! Form::label('For restaurant','For restaurant', ['class' => 'col-6 control-label']) !!}
    <div class="col-4">
      @if($order->for_restaurants)
        <p><span class='badge badge-success'>{{trans('lang.yes')}}</span></p>
      @else
        <p><span class='badge badge-danger'>{{trans('lang.no')}}</span></p>
      @endif
    </div>

    {!! Form::label('order_client_phone', trans('lang.order_client_phone'), ['class' => 'col-6 control-label']) !!}
  <div class="col-4">
    <p>{!! $order->unregistered_customer->phone ?? (isset($order->user->custom_fields['phone']) ? $order->user->custom_fields['phone']['view'] : "---------") !!}</p>
  </div>

    {!! Form::label('delivery_address', trans('lang.delivery_address'), ['class' => 'col-6 control-label']) !!}
  <div class="col-4">
    <p>{!! $order->deliveryAddress ? $order->deliveryAddress->address : '' !!}</p>
  </div>

    {!! Form::label('order_date', trans('lang.order_date'), ['class' => 'col-6 control-label']) !!}
    <div class="col-6">
    <p>{!! $order->created_at !!}</p>
  </div>


</div>

<!-- Order Status Id Field -->
<div class="form-group row col-md-6 col-sm-12">
    {!! Form::label('order_status_id', trans('lang.order_status_status'), ['class' => 'col-6 control-label']) !!}
    <div class="col-4">
    <p>{!! $order->orderStatus->status  !!}</p>
  </div>

    {!! Form::label('active', trans('lang.order_active'), ['class' => 'col-6 control-label']) !!}
    <div class="col-4">
    @if($order->active)
      <p><span class='badge badge-success'> {{trans('lang.yes')}}</span></p>
      @else
      <p><span class='badge badge-danger'>{{trans('lang.order_canceled')}}</span></p>
      @endif
  </div>

    {!! Form::label('payment_method', trans('lang.payment_method'), ['class' => 'col-6 control-label']) !!}
    <div class="col-6">
    <p>{!! isset($order->payment) ? $order->payment->method : ''  !!}</p>
  </div>

    {!! Form::label('payment_status', trans('lang.payment_status'), ['class' => 'col-6 control-label']) !!}
    <div class="col-4">
    <p>{!! isset($order->payment) ? $order->payment->status : trans('lang.order_not_paid')  !!}</p>
  </div>
    {!! Form::label('order_updated_date', trans('lang.order_updated_at'), ['class' => 'col-6 control-label']) !!}
    <div class="col-6">
        <p>{!! $order->updated_at !!}</p>
    </div>

</div>

<!-- Id Field -->
<div class="form-group row col-md-6 col-sm-12">
    {!! Form::label('restaurant', trans('lang.restaurant'), ['class' => 'col-6 control-label']) !!}
    <div class="col-4">
        @if(isset($order->foodOrders[0]))
            <p>{!! $order->foodOrders[0]->food->restaurant->name !!}</p>
        @endif
    </div>

    {!! Form::label('restaurant_address', trans('lang.restaurant_address'), ['class' => 'col-6 control-label']) !!}
    <div class="col-6">
        @if(isset($order->foodOrders[0]))
            <p>{!! $order->foodOrders[0]->food->restaurant->address !!}</p>
        @endif
    </div>

    {!! Form::label('restaurant_phone', trans('lang.restaurant_phone'), ['class' => 'col-6 control-label']) !!}
    <div class="col-4">
        @if(isset($order->foodOrders[0]))
            <p>{!! $order->foodOrders[0]->food->restaurant->phone !!}</p>
        @endif
    </div>
</div>

<div class="form-group row col-md-6 col-sm-12">

    {!! Form::label('driver', trans('lang.driver'), ['class' => 'col-6 control-label']) !!}
    <div class="col-4">
        @if(isset($order->driver))
            <p>{!! $order->driver->name !!}</p>
        @else
            <p>{{trans('lang.order_driver_not_assigned')}}</p>
        @endif

    </div>

    {!! Form::label('restaurant_delivery_fee', trans('lang.restaurant_delivery_fee'), ['class' => 'col-6 control-label']) !!}
    <div class="col-4 ">
        <p>{!! $order->restaurant_delivery_fee !!}</p>
    </div>

    {!! Form::label('hint', 'Hint:', ['class' => 'col-4 control-label']) !!}
    <div class="col-8">
        <p>{!! $order->hint !!}</p>
    </div>

</div>

{{--<!-- Tax Field -->--}}
{{--<div class="form-group row col-md-6 col-sm-12">--}}
{{--  {!! Form::label('tax', 'Tax:', ['class' => 'col-4 control-label']) !!}--}}
{{--  <div class="col-8">--}}
{{--    <p>{!! $order->tax !!}</p>--}}
{{--  </div>--}}
{{--</div>--}}


