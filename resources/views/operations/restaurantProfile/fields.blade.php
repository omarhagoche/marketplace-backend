@if ($customFields)
    <h5 class="col-12 pb-4">{!! trans('lang.main_fields') !!}</h5>
@endif
<div style="flex: 50%;max-width: 50%;padding: 0 4px;" class="column">

    <div class="form-group row ">
        {!! Form::label('open_at', trans('lang.open_at'), ['class' => 'col-12 control-label=']) !!}
        <div class="col-9">
            {!! Form::time('open_at', '10:30', ['class' => 'form-control', 'placeholder' => trans('lang.open_at')]) !!}
            <div class="form-text text-muted">
                {{ trans('lang.open_at_help') }}
            </div>
        </div>
    </div>



    <!-- cuisines Field -->
    <div class="form-group row ">
        {!! Form::label('cuisines[]', trans('lang.restaurant_cuisines'), ['class' => 'col-12 control-label ']) !!}
        <div class="col-9">
            {!! Form::select('cuisines[]', $cuisine, $cuisinesSelected, ['class' => 'select2 form-control', 'multiple' => 'multiple']) !!}
            <div class="form-text text-muted">{{ trans('lang.restaurant_cuisines_help') }}</div>
        </div>
    </div>
    @hasanyrole('admin|manager')
        <!-- 'Private_drivers Field' -->
        <div class="form-group row private-drivers">
            {!! Form::label('private_drivers', trans('lang.private_drivers'), ['class' => 'col-8 control-label ']) !!}
            <div class="checkbox icheck">
                <label class="col-9 form-check-inline">
                    {!! Form::hidden('private_drivers', 1) !!}
                    {!! Form::checkbox('private_drivers') !!}
                </label>
            </div>
        </div>
        <!-- Users Field -->
        <div class="form-group row" id='restaurant-body-drivers'>
            {!! Form::label('drivers[]', trans('lang.restaurant_drivers'), ['class' => 'col-12 control-label ']) !!}
            <div class="col-10">
                {!! Form::select('drivers[]', $drivers, $driversSelected, ['class' => 'select2 form-control', 'multiple' => 'multiple']) !!}
                <div class="form-text text-muted">{{ trans('lang.restaurant_drivers_help') }}</div>
            </div>
        </div>
        <div class="form-group row ">
            {!! Form::label('users[]', trans('lang.restaurant_users'), ['class' => 'col-12 control-label ']) !!}
            <div class="col-10">
                {!! Form::select('users[]', $users, $usersSelected, ['class' => 'select2 form-control', 'multiple' => 'multiple']) !!}
                <div class="form-text text-muted">{{ trans('lang.restaurant_delivery_price_type_help') }}</div>
            </div>
        </div>
        <div class="form-group row ">
            {!! Form::label('delivery_price_type', trans('lang.restaurant_delivery_price_type'), ['class' => 'col-12 control-label ']) !!}
            <div class="col-10">
                {!! Form::select('delivery_price_type', getDeliveryPriceTypes(), $restaurant->delivery_price_type ?? null, ['class' => 'form-control']) !!}
                <div class="form-text text-muted">{{ trans('lang.restaurant_delivery_price_type_help') }}</div>
            </div>
        </div>
        <!-- delivery_fee Field -->
        <div class="form-group row " id="delivery_fee_form_group">
            {!! Form::label('delivery_fee', trans('lang.restaurant_delivery_fee'), ['class' => 'col-12 control-label ']) !!}
            <div class="col-10">
                {!! Form::number('delivery_fee', null, ['class' => 'form-control', 'step' => 'any', 'placeholder' => trans('lang.restaurant_delivery_fee_placeholder')]) !!}
                <div class="form-text text-muted">
                    {{ trans('lang.restaurant_delivery_fee_help') }}
                </div>
            </div>
        </div>

        <!-- delivery_range Field -->
        <div class="form-group row ">
            {!! Form::label('delivery_range', trans('lang.restaurant_delivery_range'), ['class' => 'col-12 control-label ']) !!}
            <div class="col-9">
                {!! Form::number('delivery_range', null, ['class' => 'form-control', 'step' => 'any', 'placeholder' => trans('lang.restaurant_delivery_range_placeholder')]) !!}
                <div class="form-text text-muted">
                    {{ trans('lang.restaurant_delivery_range_help') }}
                </div>
            </div>
        </div>
    @endhasanyrole

</div>
<div style="flex: 50%;max-width: 50%;padding: 0 4px;" class="column">
    <div class="form-group row ">
        {!! Form::label('close_at', trans('lang.close_at'), ['class' => 'col-12 control-label']) !!}
        <div class="col-9">
            {!! Form::time('close_at', '23:30', ['class' => 'form-control', 'placeholder' => trans('lang.close_at')]) !!}
            <div class="form-text text-muted">
                {{ trans('lang.close_at_help') }}
            </div>
        </div>
    </div>


    <!-- commission Field -->
    <div class="form-group row ">
        {!! Form::label('admin_commission', trans('lang.restaurant_commission'), ['class' => 'col-12 control-label']) !!}
        <div class="col-9">
            {!! Form::text('admin_commission', $restaurant->admin_commission == null ? '10' : $restaurant->admin_commission, ['class' => 'form-control', 'placeholder' => trans('lang.restaurant_commission_placeholder')]) !!}
            <div class="form-text text-muted">
                {{ trans('lang.restaurant_commission_help') }}
            </div>
        </div>
    </div>

    <!-- default_tax Field -->
    <div class="form-group row ">
        {!! Form::label('default_tax', trans('lang.restaurant_default_tax'), ['class' => 'col-12 control-label ']) !!}
        <div class="col-9">
            {!! Form::number('default_tax', null, ['class' => 'form-control', 'step' => 'any', 'placeholder' => trans('lang.restaurant_default_tax_placeholder')]) !!}
            <div class="form-text text-muted">
                {{ trans('lang.restaurant_default_tax_help') }}
            </div>
        </div>
    </div>

    <!-- Image Field -->
    <div class="form-group row">
        {!! Form::label('image', trans('lang.restaurant_image'), ['class' => 'col-12 control-label ']) !!}
        <div class="col-9">
            <div style="width: 100%" class="dropzone image" id="image" data-field="image">
                <input type="hidden" name="image">
            </div>
            <a href="#loadMediaModal" data-dropzone="image" data-toggle="modal" data-target="#mediaModal"
                class="btn btn-outline-{{ setting('theme_color', 'primary') }} btn-sm float-right mt-1">{{ trans('lang.media_select') }}</a>
            <div class="form-text text-muted w-50">
                {{ trans('lang.restaurant_image_help') }}
            </div>
        </div>
    </div>
    @prepend('scripts')
        <script type="text/javascript">
            var var15671147011688676454ble = '';
            @if (isset($restaurant) && $restaurant->hasMedia('image'))
                var15671147011688676454ble = {
                name: "{!! $restaurant->getFirstMedia('image')->name !!}",
                size: "{!! $restaurant->getFirstMedia('image')->size !!}",
                type: "{!! $restaurant->getFirstMedia('image')->mime_type !!}",
                collection_name: "{!! $restaurant->getFirstMedia('image')->collection_name !!}"
                };
            @endif
            var dz_var15671147011688676454ble = $(".dropzone.image").dropzone({
                url: "{!! url('uploads/store') !!}",
                addRemoveLinks: true,
                maxFiles: 1,
                init: function() {
                    @if (isset($restaurant) && $restaurant->hasMedia('image'))
                        dzInit(this, var15671147011688676454ble, '{!! url($restaurant->getFirstMediaUrl('image', 'thumb')) !!}')
                    @endif
                },
                accept: function(file, done) {
                    dzAccept(file, done, this.element, "{!! config('medialibrary.icons_folder') !!}");
                },
                sending: function(file, xhr, formData) {
                    dzSending(this, file, formData, '{!! csrf_token() !!}');
                },
                maxfilesexceeded: function(file) {
                    dz_var15671147011688676454ble[0].mockFile = '';
                    dzMaxfile(this, file);
                },
                complete: function(file) {
                    dzComplete(this, file, var15671147011688676454ble, dz_var15671147011688676454ble[0].mockFile);
                    dz_var15671147011688676454ble[0].mockFile = file;
                },
                removedfile: function(file) {
                    dzRemoveFile(
                        file, var15671147011688676454ble, '{!! url('restaurants/remove-media') !!}',
                        'image', '{!! isset($restaurant) ? $restaurant->id : 0 !!}', '{!! url('uplaods/clear') !!}',
                        '{!! csrf_token() !!}'
                    );
                }
            });
            dz_var15671147011688676454ble[0].mockFile = var15671147011688676454ble;
            dropzoneFields['image'] = dz_var15671147011688676454ble;
        </script>

        <script>
            const el = document.getElementById('delivery_price_type');
            const elementToHide = document.getElementById('delivery_fee_form_group');
            const elementToRemoveName = document.getElementById('delivery_fee');

            function hideAndRemoveName(value) {
                if (value == 'fixed') {
                    elementToHide.style.display = ""
                    elementToRemoveName.setAttribute('name', 'delivery_fee')
                } else {
                    elementToHide.style.display = "none"
                    elementToRemoveName.removeAttribute('name');
                }
            }
            hideAndRemoveName(el.value);

            el.onchange = function(event) {
                hideAndRemoveName(el.value)
            }
        </script>

        {{-- Start events of private drivers to show/hide delivery boys depends on status --}}
        <script>
            const prviateDriversElement = $('.private-drivers input');
            const deliverBoysElement = document.getElementById('drivers[]');
            const deliverBoysGroupElement = document.getElementById('restaurant-body-drivers');

            function checkPrivateDriversStatus(value) {
                if (value) {
                    deliverBoysGroupElement.style.display = "";
                    deliverBoysElement.setAttribute('name', 'drivers[]');
                } else {
                    deliverBoysGroupElement.style.display = "none";
                    deliverBoysElement.removeAttribute('name');
                }
            }

            $(prviateDriversElement).on('ifChanged', function(event) {
                $(this).iCheck('update'); // apply input changes, which were done outside the plugin
                checkPrivateDriversStatus(event.target.checked);
            });
            $(prviateDriversElement).trigger('ifChanged')
        </script>
        {{-- End events of private drivers --}}
    @endprepend
    <!-- Information Field -->
    <div class="form-group row ">
        <div class="checkbox icheck">
            <label class="col-10 form-check-inline">
                {!! Form::hidden('active', 0) !!}
                {!! Form::checkbox('active', 1, null) !!}
            </label>
        </div>
        {!! Form::label('active', trans('lang.restaurant_active'), ['class' => 'col-8 control-label ']) !!}
    </div>
    <div class="form-group row ">
        <!-- 'Boolean is_restaurant Field' -->
        <div class="checkbox icheck">
            <label class="col-12 form-check-inline">
                {!! Form::hidden('is_restaurant', 0) !!}
                {!! Form::checkbox('is_restaurant', 1, null) !!}
            </label>
        </div>
        {!! Form::label('is_restaurant', trans('lang.restaurant_is_restaurant'), ['class' => 'col-8 control-label ']) !!}
    </div>
    <div class="form-group row ">
        <!-- 'Boolean closed Field' -->
        <div class="checkbox icheck">
            <label class="col-9 form-check-inline">
                {!! Form::hidden('closed', 0) !!}
                {!! Form::checkbox('closed', 1, null) !!}
            </label>
        </div>
        {!! Form::label('closed', trans('lang.restaurant_closed'), ['class' => 'col-8 control-label']) !!}
    </div>
    <div class="form-group row ">
        <!-- 'Boolean featured Field' -->
        <div class="checkbox icheck">
            <label class="col-9 form-check-inline">
                {!! Form::hidden('featured', 0) !!}
                {!! Form::checkbox('featured', 1, null) !!}
            </label>
        </div>
        {!! Form::label('featured', trans('lang.restaurant_featured'), ['class' => 'col-8 control-label ']) !!}
    </div>

    <div class="form-group row ">
        <div class="checkbox icheck">
            <label class="col-9 form-check-inline">
                {!! Form::hidden('available_for_delivery', 0) !!}
                {!! Form::checkbox('available_for_delivery', 1, null) !!}
            </label>
        </div>
        {!! Form::label('available_for_delivery', trans('lang.restaurant_available_for_delivery'), ['class' => 'col-8 control-label ']) !!}
    </div>
</div>


<div class="row">
    <div class="col-12 col-md-6">
        <!-- Description Field -->
        <div class="form-group row ">
            {!! Form::label('description', trans('lang.restaurant_description'), ['class' => 'col-12 control-label ']) !!}
            <div class="col-12">
                {!! Form::textarea('description', null, ['class' => 'form-control', 'placeholder' => trans('lang.restaurant_description_placeholder'), 'rows' => 2, 'cols' => 2]) !!}
                <div class="form-text text-muted">{{ trans('lang.restaurant_description_help') }}</div>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-6">
        <!-- Information Field -->
        <div class="form-group row ">
            {!! Form::label('information', trans('lang.restaurant_information'), ['class' => 'col-12 control-label ']) !!}
            <div class="col-12">
                {!! Form::textarea('information', null, ['class' => 'form-control', 'placeholder' => trans('lang.restaurant_information_placeholder')]) !!}
                <div class="form-text text-muted">{{ trans('lang.restaurant_information_help') }}</div>
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
<div class="form-group col-12 ">
    <button type="submit" class="btn btn-{{ setting('theme_color') }}"><i class="fa fa-save"></i>
        {{ trans('lang.save') }} {{ trans('lang.restaurant') }}</button>
    <a href="{!! route('restaurants.index') !!}" class="btn btn-default"><i class="fa fa-undo"></i>
        {{ trans('lang.cancel') }}</a>
</div>
