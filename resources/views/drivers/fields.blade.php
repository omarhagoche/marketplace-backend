@if ($customFields)
    <h5 class="col-12 pb-4">{!! trans('lang.main_fields') !!}</h5>
@endif
<div style="flex: 50%;max-width: 50%;padding: 0 4px;" class="column">
    {{-- create new user --}}
    @if (request()->is('*/create'))
        <!--  User name Field -->
        <div class="form-group row ">
            {!! Form::label('Name', trans('lang.user_name'), ['class' => 'col-3 control-label text-right']) !!}
            <div class="col-9">
                {!! Form::Text('name', null, ['class' => 'form-control', 'placeholder' => trans('lang.user_name_placeholder')]) !!}
                <div class="form-text text-muted">
                    {{ trans('lang.user_name_help') }}
                </div>
            </div>
        </div>
        <!--  User phone_number Field -->
        <div class="form-group row ">
            {!! Form::label('Phone', trans('lang.user_phone_number'), ['class' => 'col-3 control-label text-right']) !!}
            <div class="col-9">
                {!! Form::Text('phone_number', null, ['class' => 'form-control', 'placeholder' => trans('lang.user_phone_number_placeholder')]) !!}
                <div class="form-text text-muted">
                    {{ trans('lang.user_phone_number_help') }}
                </div>
            </div>
        </div>

        <!--  User email Field -->
        <div class="form-group row ">
            {!! Form::label('Email', trans('lang.user_email'), ['class' => 'col-3 control-label text-right']) !!}
            <div class="col-9">
                {!! Form::Email('email', null, ['class' => 'form-control', 'placeholder' => trans('lang.user_email_placeholder')]) !!}
                <div class="form-text text-muted">
                    {{ trans('lang.user_email_help') }}
                </div>
            </div>
        </div>
        <div class="form-group row ">
            {!! Form::label('active', trans('lang.user_active'), ['class' => 'col-3 control-label text-right']) !!}
            <div class="checkbox icheck">
                <label class="col-9 ml-2 form-check-inline">
                    {!! Form::hidden('active', 0) !!}
                    {!! Form::checkbox('active', 1, null) !!}
                </label>
            </div>
        </div>
        <!--  User Password Field -->
        <div class="form-group row ">
            {!! Form::label('Password', trans('lang.user_password'), ['class' => 'col-3 control-label text-right']) !!}
            <div class="col-9">
                {!! Form::Password('password', null, ['class' => 'form-control', 'placeholder' => trans('lang.user_passwor_placeholder')]) !!}
                <div class="form-text text-muted">
                    {{ trans('lang.user_password_help') }}
                </div>
            </div>
        </div>
        <!--  User Password Field -->
        <div class="form-group row ">
            {!! Form::label('Password', trans('lang.user_password_confirmation'), ['class' => 'col-3 control-label text-right']) !!}
            <div class="col-9">
                {!! Form::Password('password_confirmation', null, ['class' => 'form-control', 'placeholder' => trans('lang.user_password_confirmation_placeholder')]) !!}
                <div class="form-text text-muted">
                    {{ trans('lang.user_password_confirmation_help') }}
                </div>
            </div>
        </div>
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
