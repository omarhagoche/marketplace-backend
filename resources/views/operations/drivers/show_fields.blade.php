<!-- Id Field -->
<div class="form-group row col-6">
    {!! Form::label('id', 'Id:', ['class' => 'col-3 control-label text-right']) !!}
    <div class="col-9">
        <p>{!! $driver->id !!}</p>
    </div>
</div>

<!-- User Id Field -->
<div class="form-group row col-6">
    {!! Form::label('user_id', 'User Id:', ['class' => 'col-3 control-label text-right']) !!}
    <div class="col-9">
        <p>{!! $user->id !!}</p>
    </div>
</div>

<!-- User Name Field -->
<div class="form-group row col-6">
    {!! Form::label('user_name', 'User Name:', ['class' => 'col-3 control-label text-right']) !!}
    <div class="col-9">
        <p>{!! $user->name !!}</p>
    </div>
</div>

<!-- User Phone Field -->
<div class="form-group row col-6">
    {!! Form::label('phone_number', 'User Phone:', ['class' => 'col-3 control-label text-right']) !!}
    <div class="col-9">
        <p>{!! $user->phone_number !!}</p>
    </div>
</div>

<!-- User Email Field -->
<div class="form-group row col-6">
    {!! Form::label('Email', 'User Email:', ['class' => 'col-3 control-label text-right']) !!}
    <div class="col-9">
        <p>{!! $user->email !!}</p>
    </div>
</div>

<!-- User On_Order Field -->
<div class="form-group row col-6">
    {!! Form::label('on_order', 'is he/she on Order:', ['class' => 'col-3 control-label text-right']) !!}
    <div class="col-9">
        @if ($driver->working_on_order)
            <p>{{ trans('lang.yes') }}</p>
        @else
            <p>{{ trans('lang.no') }}</p>
        @endif
    </div>
</div>

<!-- User Active Field -->
<div class="form-group row col-6">
    {!! Form::label('Active', 'is he/she Active:', ['class' => 'col-3 control-label text-right']) !!}
    <div class="col-9">
        @if ($user->active)
            <p>{{ trans('lang.yes') }}</p>
        @else
            <p>{{ trans('lang.no') }}</p>
        @endif
    </div>
</div>
<!-- Delivery Fee Field -->
<div class="form-group row col-6">
    {!! Form::label('delivery_fee', 'Delivery Fee:', ['class' => 'col-3 control-label text-right']) !!}
    <div class="col-9">
        <p>{!! $driver->delivery_fee !!}</p>
    </div>
</div>

<!-- Driver Type Field -->
<div class="form-group row col-6">
    {!! Form::label('type', 'Driver Type:', ['class' => 'col-3 control-label text-right']) !!}
    <div class="col-9">
        {{-- <p>{!! $driver->driverType->name !!}</p> --}}
    </div>
</div>

<!-- Total Orders Field -->
<div class="form-group row col-6">
    {!! Form::label('total_orders', 'Total Orders:', ['class' => 'col-3 control-label text-right']) !!}
    <div class="col-9">
        <p>{!! $driver->orders->count() !!}</p>
    </div>
</div>

<!-- Earning Field -->
<div class="form-group row col-6">
    {!! Form::label('earning', 'Earning:', ['class' => 'col-3 control-label text-right']) !!}
    <div class="col-9">
        <p>{!! $driver->earning !!}</p>
    </div>
</div>

<!-- Available Field -->
<div class="form-group row col-6">
    {!! Form::label('available', 'Available:', ['class' => 'col-3 control-label text-right']) !!}
    <div class="col-9">
        @if ($driver->available)
            <p>{{ trans('lang.yes') }}</p>
        @else
            <p>{{ trans('lang.no') }}</p>
        @endif

    </div>
</div>

<!-- Created At Field -->
<div class="form-group row col-6">
    {!! Form::label('created_at', 'Created At:', ['class' => 'col-3 control-label text-right']) !!}
    <div class="col-9">
        <p>{!! $driver->created_at !!}</p>
    </div>
</div>

<!-- Updated At Field -->
<div class="form-group row col-6">
    {!! Form::label('updated_at', 'Updated At:', ['class' => 'col-3 control-label text-right']) !!}
    <div class="col-9">
        <p>{!! $driver->updated_at !!}</p>
    </div>
</div>
<div class="form-group row col-6">
    {!! Form::label('note', 'Note:', ['class' => 'col-3 control-label text-right']) !!}
    <div class="col-9">
        <p>{!! $driver->note ?? '' !!}</p>
    </div>
</div>

<div class="form-group row col-6">
    {!! Form::label('ordersOfDay', 'Orders of this Day:', ['class' => 'col-3 control-label text-right']) !!}
    <div class="col-4">
        <p>{!! $ordersOfDay !!}</p>
    </div>
</div>

<div class="form-group row col-6">
    {!! Form::label('ordersOfWeek', 'Orders Of This Week:', ['class' => 'col-3 control-label text-right']) !!}
    <div class="col-4">
        <p>{!! $ordersOfWeek !!}</p>
    </div>
</div>

<div class="form-group row col-6">
    {!! Form::label('ordersOfMonth', 'Orders Of This  Month:', ['class' => 'col-3 control-label text-right']) !!}
    <div class="col-4">
        <p>{!! $ordersOfMonth !!}</p>
    </div>
</div>
