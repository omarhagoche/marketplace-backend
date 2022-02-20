@if ($customFields)
    <h5 class="col-12 pb-4">{!! trans('lang.main_fields') !!}</h5>
@endif
<div style="flex: 50%;max-width: 50%;padding: 0 4px;" class="column">
    {{-- create new user --}}
    @if (request()->is('*/create'))
        @include('drivers.user_fields')
    @endif
    <!-- Delivery Fee Field -->
    <div class="form-group row ">
        {!! Form::label('delivery_fee', trans('lang.driver_delivery_fee'), ['class' => 'col-3 control-label text-right']) !!}
        <div class="col-9">
            {!! Form::number('delivery_fee', null, ['class' => 'form-control', 'placeholder' => trans('lang.driver_delivery_fee_placeholder')]) !!}
            <div class="form-text text-muted">
                {{ trans('lang.driver_delivery_fee_help') }}
            </div>
        </div>
    </div>
    <!-- Note Field -->
    <div class="form-group row ">
        {!! Form::label('note', trans('lang.driver_note'), ['class' => 'col-3 control-label text-right']) !!}
        <div class="col-9">
            {!! Form::Text('note', null, ['class' => 'form-control', 'placeholder' => trans('lang.driver_note_placeholder')]) !!}
            <div class="form-text text-muted">
                {{ trans('lang.driver_note_help') }}
            </div>
        </div>
    </div>

    <!-- User phone_number Field -->
    <div class="form-group row ">
        {!! Form::label('phone_number', trans('lang.phone_number'), ['class' => 'col-3 control-label text-right']) !!}
        <div class="col-9">
            {!! Form::Text('phone_number', $user->phone_number ?? null, ['class' => 'form-control', 'placeholder' => trans('lang.phone_number_placeholder')]) !!}
            <div class="form-text text-muted">
                {{ trans('lang.phone_number_help') }}
            </div>
        </div>
    </div>
    <!-- User email Field -->
    <div class="form-group row ">
        {!! Form::label('email', trans('lang.email'), ['class' => 'col-3 control-label text-right']) !!}
        <div class="col-9">
            {!! Form::Email('email', $user->email ?? null, ['class' => 'form-control', 'placeholder' => trans('lang.email_placeholder')]) !!}
            <div class="form-text text-muted">
                {{ trans('lang.email_help') }}
            </div>
        </div>
    </div>

    <!-- Driver type ID Field -->
    <div class="form-group row ">
        {!! Form::label('driver_type_id', trans('lang.driver_type_id'), ['class' => 'col-3 control-label text-right']) !!}
        <div class="col-9">
            {!! Form::select('driver_type_id', $types, null, ['class' => 'select2 form-control']) !!}
            <div class="form-text text-muted">{{ trans('lang.driver_type_id_help') }}</div>
        </div>
    </div>


    <!-- 'Boolean Available Field' -->
    <div class="form-group row ">
        {!! Form::label('available', trans('lang.driver_available'), ['class' => 'col-3 control-label text-right']) !!}
        <div class="checkbox icheck">
            <label class="col-9 ml-2 form-check-inline">
                {!! Form::hidden('available', 0) !!}
                {!! Form::checkbox('available', 1, null) !!}
            </label>
        </div>
    </div>


    {{-- <!-- 'Driver Type Field' -->
    <div class="form-group row ">
        {!! Form::label('type', trans('lang.driver_type'), ['class' => 'col-3 control-label text-right']) !!}
        <div class="col-9">
            {!! Form::select('type', $driver->types(), $driver->type, ['class' => 'form-control']) !!}
            <div class="form-text text-muted">{{ trans('lang.driver_type_help') }}</div>
        </div>
    </div> --}}

    <!-- Driver type ID Field -->
    <div class="form-group row ">
        {!! Form::label('driver_type_id', trans('lang.driver_type_id'), ['class' => 'col-3 control-label text-right']) !!}
        <div class="col-9">
            {!! Form::select('driver_type_id', $types, null, ['class' => 'select2 form-control']) !!}
            <div class="form-text text-muted">{{ trans('lang.driver_type_id_help') }}</div>
        </div>
    </div>


    <!-- 'Boolean Available Field' -->
    <div class="form-group row ">
        {!! Form::label('available', trans('lang.driver_available'), ['class' => 'col-3 control-label text-right']) !!}
        <div class="checkbox icheck">
            <label class="col-9 ml-2 form-check-inline">
                {!! Form::hidden('available', 0) !!}
                {!! Form::checkbox('available', 1, null) !!}
            </label>
        </div>
        {!! Form::label('active', trans('lang.user_active'), ['class' => 'col-3 control-label text-right']) !!}
        <div class="checkbox icheck">
            <label class="col-9 ml-2 form-check-inline">
                {!! Form::hidden('active', 0) !!}
                {!! Form::checkbox('active', 1, $user->active ?? 0) !!}
            </label>
        </div>
    </div>

</div>
@if ($customFields)
    <div class="clearfix"></div>
    <div class="col-12 custom-field-container">
        <h5 class="col-12 pb-4">{!! trans('lang.custom_field_plural') !!}</h5>
        {!! $customFields !!}
    </div>
@endif
<!-- Submit Field -->
<div class="form-group col-12 text-right">
    <button type="submit" class="btn btn-{{ setting('theme_color') }}"><i class="fa fa-save"></i>
        {{ trans('lang.save') }} {{ trans('lang.driver') }}</button>
    <a href="{!! route('drivers.index') !!}" class="btn btn-default"><i class="fa fa-undo"></i>
        {{ trans('lang.cancel') }}</a>
</div>
