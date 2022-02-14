{{-- {{ $order->restaurantCoupon }}
{{ $order->restaurantCoupon }}
{{ $order }} --}}

<div disabled style="flex: 50%;max-width: 50%;padding:17px; border-style: solid; border-width: 1px; border-radius: 9px; border-color: #b5b5b538;" class="column">
   {!! Form::open(['route' => ['orders.store-order-restaurant-coupon',$order->id], 'method' => 'post']) !!}
   <div class="form-group row ">
       <span>{{ trans('lang.coupon_restaurant') }}</span>
   </div>
    @if (!$order->restaurantCoupon)

    <div class="form-group row ">
        {!! Form::label('code', trans("lang.coupon_code"),['class' => 'col-3 control-label']) !!}
        <div class="col-9">
            {!! Form::text('code', null,  ['class' => 'form-control', 'placeholder'=>  trans("lang.coupon_code")]) !!}
        </div>
    </div>
    <div class="form-group row ">
        {!! Form::label('discount', trans("lang.coupon_discount"),['class' => 'col-3 control-label']) !!}
        <div class="col-9">
            {!! Form::text('discount', null,  ['class' => 'form-control', 'placeholder'=>  trans("lang.coupon_discount")]) !!}
        </div>
    </div>
    <div class="form-group row ">
        {!! Form::label('discount_type', trans("lang.coupon_discount_type_help"),['class' => 'col-3 control-label']) !!}
        <div class="col-9">
            {!! Form::select('discount_type', ['percent' => trans('lang.coupon_percent'),'fixed' => trans('lang.coupon_fixed')], null, ['class' => 'select2 form-control']) !!}
        </div>
    </div>

    <div class="form-group row ">
        {!! Form::label('cost_on_restaurant', trans("lang.coupon_cost_on_restaurant"),['class' => 'col-3 control-label']) !!}
        <div class="checkbox icheck col-9">
            <label class="form-check-inline">
                {!! Form::hidden('cost_on_restaurant', 0) !!}
                {!! Form::checkbox('cost_on_restaurant', 1, null) !!}
            </label>
        </div>
    </div>
    <div class="form-group row ">
        {!! Form::checkbox('enabled', 1,1,["hidden" => "hidden"]) !!}
        {!! Form::number('count_used', 1,["hidden" => "hidden"]) !!}
        {!! Form::number('count', 1,["hidden" => "hidden"]) !!}
    </div>
    <div class="form-group row ">
        <input hidden name="expires_at" type="date" value="<?php echo date("Y-m-d");?>">
        <button type="submit" class="btn btn-{{setting('theme_color')}}"><i class="fa fa-save"></i> {{trans('lang.save')}}</button>
    </div>
     @else
     <fieldset disabled="disabled">
        <div class="form-group row ">
            {!! Form::label('code', trans("lang.coupon_code"),['class' => 'col-3 control-label']) !!}
            <div class="col-9">
                {!! Form::text('code', $order->restaurantCoupon->code,  ['class' => 'form-control', 'placeholder'=>  trans("lang.coupon_code")]) !!}
            </div>
        </div>
        <div class="form-group row ">
            {!! Form::label('discount', trans("lang.coupon_discount"),['class' => 'col-3 control-label']) !!}
            <div class="col-9">
                {!! Form::text('discount', $order->restaurantCoupon->discount,  ['class' => 'form-control', 'placeholder'=>  trans("lang.coupon_discount")]) !!}
            </div>
        </div>
        <div class="form-group row ">
            {!! Form::label('discount_type', trans("lang.coupon_discount_type_help"),['class' => 'col-3 control-label']) !!}
            <div class="col-9">
                {!! Form::text('discount_type', $order->restaurantCoupon->discount_type,  ['class' => 'form-control', 'placeholder'=>  trans("lang.coupon_discount_type_help")]) !!}
            </div>
        </div>
    
        <div class="form-group row ">
            {!! Form::label('cost_on_restaurant', trans("lang.coupon_cost_on_restaurant"),['class' => 'col-3 control-label']) !!}
            <div class="checkbox icheck col-9">
                <label class="form-check-inline">
                    {!! Form::checkbox('cost_on_restaurant', 1, $order->restaurantCoupon->cost_on_restaurant,["disabled" => "disabled"]) !!}
                </label>
            </div>
        </div>
     </fieldset>
    @endif
    {!! Form::close() !!}
</div>
<div style="flex: 50%;max-width: 50%;padding:17px; border-style: solid; border-width: 1px; border-radius: 9px; border-color: #b5b5b538;" class="column">
    <div class="form-group row ">
        <span>{{ trans('lang.coupon_delivery') }}</span>
    </div>
    {!! Form::open(['route' => ['orders.store-order-delivery-coupon',$order->id], 'method' => 'post']) !!}
    @if (!$order->deliveryCoupon)

    <div class="form-group row ">
        {!! Form::label('code', trans("lang.coupon_code"),['class' => 'col-3 control-label']) !!}
        <div class="col-9">
            {!! Form::text('code', null,  ['class' => 'form-control', 'placeholder'=>  trans("lang.coupon_code")]) !!}
        </div>
    </div>
    <div class="form-group row ">
        {!! Form::label('discount', trans("lang.coupon_discount"),['class' => 'col-3 control-label']) !!}
        <div class="col-9">
            {!! Form::text('discount', null,  ['class' => 'form-control', 'placeholder'=>  trans("lang.coupon_discount")]) !!}
        </div>
    </div>
    <div class="form-group row ">
        {!! Form::label('discount_type', trans("lang.coupon_discount_type_help"),['class' => 'col-3 control-label']) !!}
        <div class="col-9">
            {!! Form::select('discount_type', ['percent' => trans('lang.coupon_percent'),'fixed' => trans('lang.coupon_fixed')], null, ['class' => 'select2 form-control']) !!}
        </div>
    </div>

    <div class="form-group row ">
        {!! Form::label('on_delivery_fee', trans("lang.coupon_on_delivery_fee"),['class' => 'col-3 control-label']) !!}
        <div class="checkbox icheck col-9">
            <label class="form-check-inline">
                {!! Form::hidden('on_delivery_fee', 0) !!}
                {!! Form::checkbox('on_delivery_fee', 1, null) !!}
            </label>
        </div>
    </div>
    <div class="form-group row ">
        {!! Form::checkbox('enabled', 1,1,["hidden" => "hidden"]) !!}
        {!! Form::number('count_used', 1,["hidden" => "hidden"]) !!}
        {!! Form::number('count', 1,["hidden" => "hidden"]) !!}
    </div>
    <div class="form-group row ">
        <input hidden name="expires_at" type="date" value="<?php echo date("Y-m-d");?>">
        <button type="submit" class="btn btn-{{setting('theme_color')}}"><i class="fa fa-save"></i> {{trans('lang.save')}}</button>
    </div>
     @else
     <fieldset disabled="disabled">
        <div class="form-group row ">
            {!! Form::label('code', trans("lang.coupon_code"),['class' => 'col-3 control-label']) !!}
            <div class="col-9">
                {!! Form::text('code', $order->deliveryCoupon->code,  ['class' => 'form-control', 'placeholder'=>  trans("lang.coupon_code")]) !!}
            </div>
        </div>
        <div class="form-group row ">
            {!! Form::label('discount', trans("lang.coupon_discount"),['class' => 'col-3 control-label']) !!}
            <div class="col-9">
                {!! Form::text('discount', $order->deliveryCoupon->discount,  ['class' => 'form-control', 'placeholder'=>  trans("lang.coupon_discount")]) !!}
            </div>
        </div>
        <div class="form-group row ">
            {!! Form::label('discount_type', trans("lang.coupon_discount_type_help"),['class' => 'col-3 control-label']) !!}
            <div class="col-9">
                {!! Form::text('discount_type', $order->deliveryCoupon->discount_type,  ['class' => 'form-control', 'placeholder'=>  trans("lang.coupon_discount_type_help")]) !!}
            </div>
        </div>
    
        <div class="form-group row ">
            {!! Form::label('on_delivery_fee', trans("lang.coupon_on_delivery_fee"),['class' => 'col-3 control-label']) !!}
            <div class="checkbox icheck col-9">
                <label class="form-check-inline">
                    {!! Form::checkbox('on_delivery_fee', 1, $order->deliveryCoupon->on_delivery_fee,["disabled" => "disabled"]) !!}
                </label>
            </div>
        </div>
     </fieldset>
    @endif
    {!! Form::close() !!}
</div>

