@if($customFields)
    <h5 class="col-12 pb-4">{!! trans('lang.main_fields') !!}</h5>
@endif
<div style="flex: 50%;max-width: 50%;padding: 0 4px;" class="column pl-3">

    <!-- User Id Field -->
    @if($order->user_id)
    <div class="form-group row ">
        {!! Form::label('user_id', trans("lang.order_user_id"),['class' => 'col-4 control-label ']) !!}
        <div class="col-8">
            {{ $order->user->name}}
        </div>
    </div>
    @endif

    <!-- Driver Id Field -->
    <div class="form-group row ">
        {!! Form::label('driver_id', trans("lang.order_driver_id"),['class' => 'col-4 control-label ']) !!}
        <div class="col-8">
            {!! Form::select('driver_id', $driver, null, ['data-empty'=>trans("lang.order_driver_id_placeholder"),'class' => 'select2 not-required form-control']) !!}
            <div class="form-text text-muted">{{ trans("lang.order_driver_id_help") }}</div>
        </div>
    </div>

    <!-- Order Status Id Field -->
    <div class="form-group row ">
        {!! Form::label('order_status_id', trans("lang.order_order_status_id"),['class' => 'col-4 control-label ']) !!}
        <div class="col-8">
            {!! Form::select('order_status_id', $orderStatus, null, ['class' => 'select2 form-control']) !!}
            <div class="form-text text-muted">{{ trans("lang.order_order_status_id_help") }}</div>
        </div>
    </div>


    <div class="form-group row ">
        {!! Form::label('costumer_adresses', trans("lang.delivery_address"),['class' => 'col-4 control-label ']) !!}
        <div class="col-8">
            {!! Form::select('delivery_address_id', $userAddresses, optional($order->deliveryAddress)->id ,['class' => 'select2 form-control']) !!}
        </div>
    </div>
    <!-- Status Field -->
    <div class="form-group row ">
        {!! Form::label('status', trans("lang.payment_status"),['class' => 'col-4 control-label ']) !!}
        <div class="col-8">
            {!! Form::select('status',
            [
            'Waiting for Client' => trans('lang.order_pending'),
            'Not Paid' => trans('lang.order_not_paid'),
            'Paid' => trans('lang.order_paid'),
            ]
            , isset($order->payment) ? $order->payment->status : '', ['class' => 'select2 form-control']) !!}
            <div class="form-text text-muted">{{ trans("lang.payment_status_help") }}</div>
        </div>
    </div>
    <!-- 'Boolean active Field' -->
    <div class="form-group row ">
        {!! Form::label('active', trans("lang.order_active"),['class' => 'col-5 control-label ']) !!}
        <div class="checkbox icheck">
            <label class="col-7 ml-2 form-check-inline">
                {!! Form::hidden('active', 0) !!}
                {!! Form::checkbox('active', 1, null) !!}
            </label>
        </div>
    </div>
    <!-- 'Boolean for_restaurants Field' -->
    <div class="form-group row ">
        {!! Form::label('for_restaurants', trans("lang.order_for_restaurants"),['class' => 'col-5 control-label  ']) !!}
        <div class="checkbox icheck">
            <label class="col-7 ml-2 form-check-inline">
                {!! Form::hidden('for_restaurants', 0) !!}
                {!! Form::checkbox('for_restaurants', 1, null) !!}
            </label>
        </div>
    </div>

</div>
<div style="flex: 50%;max-width: 50%;padding: 0 4px;" class="column pl-4">

    <!-- Processing time Field -->
    <div class="form-group row ">
        {!! Form::label('processing_time', trans("lang.order_processing_time"), ['class' => 'col-4 control-label ']) !!}
        <div class="col-8">
            {!! Form::number('processing_time', null,  ['class' => 'form-control', 'min' => 0, 'placeholder'=>  trans("lang.order_processing_time_placeholder")]) !!}
            <div class="form-text text-muted">
                {{ trans("lang.order_processing_time_help") }}
            </div>
        </div>
    </div>


    <!-- delivery_fee Field -->
    <div class="form-group row ">
        {!! Form::label('delivery_fee', trans("lang.order_delivery_fee"), ['class' => 'col-4 control-label ']) !!}
        <div class="col-8">
            {!! Form::number('delivery_fee', null,  ['class' => 'form-control','step'=>"any",'placeholder'=>  trans("lang.order_delivery_fee_placeholder")]) !!}
            <div class="form-text text-muted">
                {{ trans("lang.order_delivery_fee_help") }}
            </div>
        </div>
    </div>
    <!-- Hint Field -->
    <div class="form-group row ">
        {!! Form::label('hint', trans("lang.order_hint"), ['class' => 'col-4 control-label ']) !!}
        <div class="col-8">
            {!! Form::textarea('hint', null, ['class' => 'form-control','placeholder'=>
             trans("lang.order_hint_placeholder")  ]) !!}
            <div class="form-text text-muted">{{ trans("lang.order_hint_help") }}</div>
        </div>
    </div>
</div>
@if($customFields)
    <div class="clearfix"></div>
    <div class="col-12 custom-field-container">
        <h5 class="col-12 pb-4">{!! trans('lang.custom_field_plural') !!}</h5>
        {!! $customFields !!}
    </div>
@endif

<!-- Submit Field -->
<div class="form-group col-12 ">
    <!-- 'Confirm Field' -->
    <div class="form-group row ">
        {!! Form::label('for_restaurants', 'I am sure the order status is compatible with the driver' ,['class' => 'col-auto control-label ']) !!}
        <div class="checkbox icheck">
            <label class="col ml-2 form-check-inline">
                {!! Form::checkbox('confirm', 0, 0, ['required']) !!}
            </label>
        </div>
    </div>
    <button type="submit" class="btn btn-{{setting('theme_color')}}"><i class="fa fa-save"></i> {{trans('lang.save')}} {{trans('lang.order')}}</button>
    <a href="{!! route('operations.orders.index') !!}" class="btn btn-default"><i class="fa fa-undo"></i> {{trans('lang.cancel')}}</a>
</div>

