<div class="card-header"> 
    <div class="col-12" style="text-align: center;"> 
        <img src="{{ $restaurant->media->first()->getUrl()}}" alt="Avatar" class="avatar">
    </div>
    <!-- Name Field -->
    <div class="form-group row ">
        {!! Form::label('name', trans("lang.restaurant_name"), ['class' => 'col-3 control-label']) !!}
        <div class="col-9">
            {!! Form::text('name', null,  ['class' => 'form-control','placeholder'=>  trans("lang.restaurant_name_placeholder")]) !!}
            <div class="form-text text-muted">
                {{ trans("lang.restaurant_name_help") }}
            </div>
        </div>
    </div>
    <!-- Phone Field -->
    <div class="form-group row ">
        {!! Form::label('phone', trans("lang.restaurant_phone"), ['class' => 'col-3 control-label']) !!}
        <div class="col-9">
            {!! Form::text('phone', null,  ['class' => 'form-control','placeholder'=>  trans("lang.restaurant_phone_placeholder")]) !!}
            <div class="form-text text-muted">
                {{ trans("lang.restaurant_phone_help") }}
            </div>
        </div>
    </div>
    <!-- Mobile Field -->
    <div class="form-group row ">
        {!! Form::label('mobile', trans("lang.restaurant_mobile"), ['class' => 'col-3 control-label']) !!}
        <div class="col-9">
            {!! Form::text('mobile', null,  ['class' => 'form-control','placeholder'=>  trans("lang.restaurant_mobile_placeholder")]) !!}
            <div class="form-text text-muted">
                {{ trans("lang.restaurant_mobile_help") }}
            </div>
        </div>
    </div>
    <!-- Address Field -->
    <div class="form-group row ">
        {!! Form::label('address', trans("lang.restaurant_address"), ['class' => 'col-3 control-label']) !!}
        <div class="col-9">
            {!! Form::text('address', null,  ['class' => 'form-control','placeholder'=>  trans("lang.restaurant_address_placeholder")]) !!}
            <div class="form-text text-muted">
                {{ trans("lang.restaurant_address_help") }}
            </div>
        </div>
    </div>

    <!-- Latitude Field -->
    <div class="form-group row ">
        {!! Form::label('latitude', trans("lang.restaurant_latitude"), ['class' => 'col-3 control-label']) !!}
        <div class="col-9">
            {!! Form::text('latitude', null,  ['class' => 'form-control','placeholder'=>  trans("lang.restaurant_latitude_placeholder")]) !!}
            <div class="form-text text-muted">
                {{ trans("lang.restaurant_latitude_help") }}
            </div>
        </div>
    </div>

    <!-- Longitude Field -->
    <div class="form-group row ">
        {!! Form::label('longitude', trans("lang.restaurant_longitude"), ['class' => 'col-3 control-label']) !!}
        <div class="col-9">
            {!! Form::text('longitude', null,  ['class' => 'form-control','placeholder'=>  trans("lang.restaurant_longitude_placeholder")]) !!}
            <div class="form-text text-muted">
                {{ trans("lang.restaurant_longitude_help") }}
            </div>
        </div>
    </div>
    <div class="form-group row ">
        {!! Form::label('active', trans("lang.restaurant_active"),['class' => 'col-3 control-label']) !!}
        <div class="checkbox icheck">
            <label class="col-9 ml-2 form-check-inline">
                {!! Form::hidden('active', 0) !!}
                {!! Form::checkbox('active', 1, null) !!}
            </label>
        </div>
    </div>
    <!-- 'Boolean closed Field' -->
    <div class="form-group row ">
        {!! Form::label('closed', trans("lang.restaurant_closed"),['class' => 'col-6 control-label']) !!}
        <div class="checkbox icheck">
            <label class="col-9 ml-2 form-check-inline">
                {!! Form::hidden('closed', 0) !!}
                {!! Form::checkbox('closed', 1, null) !!}
            </label>
        </div>
    </div>
    
    <!-- 'Boolean featured Field' -->
    <div class="form-group row ">
        {!! Form::label('featured', trans("lang.restaurant_featured"),['class' => 'col-6  control-label']) !!}
        <div class="checkbox icheck">
            <label class="col-9 ml-2 form-check-inline">
                {!! Form::hidden('featured', 0) !!}
                {!! Form::checkbox('featured', 1, null) !!}
            </label>
        </div>
    </div> 
       <!-- 'Boolean is_restaurant Field' -->
       <div class="form-group row ">
        {!! Form::label('is_restaurant', trans("lang.restaurant_is_restaurant"),['class' => 'col-6 control-label']) !!}
        <div class="checkbox icheck">
            <label class="col-9 ml-2 form-check-inline">
                {!! Form::hidden('is_restaurant', 0) !!}
                {!! Form::checkbox('is_restaurant', 1, null) !!}
            </label>
        </div>
    </div>
</div>