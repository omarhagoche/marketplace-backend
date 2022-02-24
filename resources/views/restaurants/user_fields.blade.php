<h3 class="ml-3">{{ 'Create Admin User' }}</h3>
<p class="ml-3"> name of restaurant is name of admin</p>
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
