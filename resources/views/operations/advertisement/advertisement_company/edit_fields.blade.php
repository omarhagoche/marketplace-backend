<div class="row">
    <div class="card-header">
        <div class="col-12" style="text-align: center;">
            <img src="{{ $advertisement_Company->media->first() ? $advertisement_Company->media->first()->getUrl() : '' }}"
                style="width: 100px;" alt="Avatar" class="avatar">
        </div>
        <!-- Name Field -->
        <div class="form-group row mt-4 ">
            {!! Form::label('name', trans('lang.name'), ['class' => 'col-5 control-label']) !!}
            <div class="col-7">
                {!! Form::text('name', null, ['class' => 'form-control', 'placeholder' => trans('lang.name_placeholder')]) !!}
                <div class="form-text text-muted">
                    {{ trans('lang.name_help') }}
                </div>
            </div>
        </div>
        <!-- Phone Field -->
        <div class="form-group row ">
            {!! Form::label('phone', trans('lang.phone'), ['class' => 'col-5 control-label']) !!}
            <div class="col-7">
                {!! Form::text('phone', null, ['class' => 'form-control', 'placeholder' => trans('lang.phone_placeholder')]) !!}
                <div class="form-text text-muted">
                    {{ trans('lang.phone_help') }}
                </div>
            </div>
        </div>
        <!-- Mobile Field -->
        <div class="form-group row ">
            {!! Form::label('mobile', trans('lang.mobile'), ['class' => 'col-5 control-label']) !!}
            <div class="col-7">
                {!! Form::text('mobile', null, ['class' => 'form-control', 'placeholder' => trans('lang.mobile_placeholder')]) !!}
                <div class="form-text text-muted">
                    {{ trans('lang.mobile_help') }}
                </div>
            </div>
        </div>
        <!-- Address Field -->
        <div class="form-group row ">
            {!! Form::label('address', trans('lang.address'), ['class' => 'col-5 control-label']) !!}
            <div class="col-7">
                {!! Form::text('address', null, ['class' => 'form-control', 'placeholder' => trans('lang.address_placeholder')]) !!}
                <div class="form-text text-muted">
                    {{ trans('lang.address_help') }}
                </div>
            </div>
        </div>

        <!-- Latitude Field -->
        <div class="form-group row ">
            {!! Form::label('latitude', trans('lang.latitude'), ['class' => 'col-5 control-label']) !!}
            <div class="col-7">
                {!! Form::text('latitude', null, ['class' => 'form-control', 'placeholder' => trans('lang.latitude_placeholder')]) !!}
                <div class="form-text text-muted">
                    {{ trans('lang.latitude_help') }}
                </div>
            </div>
        </div>

        <!-- Longitude Field -->
        <div class="form-group row ">
            {!! Form::label('longitude', trans('lang.longitude'), ['class' => 'col-5 control-label']) !!}
            <div class="col-7">
                {!! Form::text('longitude', null, ['id' => 'longitude', 'class' => 'form-control', 'placeholder' => trans('lang.longitude_placeholder')]) !!}
                <div class="form-text text-muted">
                    {{ trans('lang.longitude_help') }}
                </div>
            </div>
        </div>
        <div class="form-group row ">
            <div id="map" style="width: 100%; height: 300px;"></div>
        </div>


    </div>

    @section('extra-js')
        <script src="https://maps.google.com/maps/api/js?key={{ setting('google_maps_key', 'AIzaSyAT07iMlfZ9bJt1gmGj9KhJDLFY8srI6dA') }}"
                type="text/javascript"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js" type="text/javascript"></script>

        <script type="text/javascript">
            window.onload = function() {

                var latlng = new google.maps.LatLng({{ $supermarket->latitude }},
                {{ $supermarket->longitude }}); //Set the default location of map

                var map = new google.maps.Map(document.getElementById('map'), {

                    center: latlng,

                    zoom: 14, //The zoom value for map

                    mapTypeId: google.maps.MapTypeId.ROADMAP

                });

                var marker = new google.maps.Marker({

                    position: latlng,

                    map: map,

                    title: 'Place the marker for your location!', //The title on hover to display

                    draggable: true //this makes it drag and drop

                });

                google.maps.event.addListener(marker, 'dragend', function(a) {

                    console.log(document.getElementById('latitude').value);
                    document.getElementById('latitude').value = a.latLng.lat().toFixed(7);
                    document.getElementById('longitude').value = a.latLng.lng().toFixed(7);
                });

                $(document).ready(function() {
                    $("#latitude").keyup(function() {
                        var latlng = new google.maps.LatLng($('#latitude').val(), $('#longitude').val());
                        map.setCenter(new google.maps.LatLng($('#latitude').val(), $('#longitude').val()));
                        marker.setPosition(latlng);
                    });
                    $("#longitude").keyup(function() {
                        var latlng = new google.maps.LatLng($('#latitude').val(), $('#longitude').val());
                        map.setCenter(new google.maps.LatLng($('#latitude').val(), $('#longitude').val()));
                        marker.setPosition(latlng);
                    });
                });
            };
        </script>
    @endsection
</div>
</div>
</div>
<div class="col-md-8 col-12">
    <div class="card">
        @include('operations.supermarkets.links', compact('id', 'supermarket'))
        <div class="card-body">
            <div class="row" style="margin-left: 29px;">
                @if ($customFields)
                    <h5 class="col-12 pb-4">{!! trans('lang.main_fields') !!}</h5>
                @endif
                <div style="flex: 50%;max-width: 50%;padding: 0 4px;" class="column">

                    <div class="form-group row ">
                        {!! Form::label('open_at', trans('lang.open_at'), ['class' => 'col-12 control-label=']) !!}
                        <div class="col-11">
                            {!! Form::time('open_at', '10:30', ['class' => 'form-control', 'placeholder' => trans('lang.open_at')]) !!}
                            <div class="form-text text-muted">
                                {{ trans('lang.open_at_help') }}
                            </div>
                        </div>
                    </div>


                    @hasanyrole('admin|manager')
                        <div class="form-group row ">
                            {!! Form::label('users[]', trans('lang.users'), ['class' => 'col-12 control-label ']) !!}
                            <div class="col-11">
                                {!! Form::select('users[]', $users, $usersSelected, ['class' => 'select2 form-control', 'multiple' => 'multiple']) !!}
                                <div class="form-text text-muted">{{ trans('lang.delivery_price_type_help') }}
                                </div>
                            </div>
                        </div>
                        <div class="form-group row ">
                            {!! Form::label('delivery_price_type', trans('lang.delivery_price_type'), ['class' => 'col-12 control-label ']) !!}
                            <div class="col-11">
                                {!! Form::select('delivery_price_type', getDeliveryPriceTypes(), $supermarket->delivery_price_type ?? null, ['class' => 'form-control']) !!}
                                <div class="form-text text-muted">{{ trans('lang.delivery_price_type_help') }}
                                </div>
                            </div>
                        </div>
                        <!-- delivery_fee Field -->
                        <div class="form-group row " id="delivery_fee_form_group">
                            {!! Form::label('delivery_fee', trans('lang.delivery_fee'), ['class' => 'col-12 control-label ']) !!}
                            <div class="col-11">
                                {!! Form::number('delivery_fee', null, ['class' => 'form-control', 'step' => 'any', 'placeholder' => trans('lang.delivery_fee_placeholder')]) !!}
                                <div class="form-text text-muted">
                                    {{ trans('lang.delivery_fee_help') }}
                                </div>
                            </div>
                        </div>

                        <!-- delivery_range Field -->
                        <div class="form-group row  ">
                            {!! Form::label('delivery_range', trans('lang.delivery_range'), ['class' => 'col-12 control-label ']) !!}
                            <div class="col-11">
                                {!! Form::number('delivery_range', null, ['class' => 'form-control', 'step' => 'any', 'placeholder' => trans('lang.delivery_range_placeholder')]) !!}
                                <div class="form-text text-muted">
                                    {{ trans('lang.delivery_range_help') }}
                                </div>
                            </div>
                        </div>
                    @endhasanyrole
                    <div class="form-group row mb-0 ">
                        <!-- 'Boolean closed Field' -->
                        <label class="col-10 mb-0  form-check-inline">
                            <input type="checkbox"  @if($supermarket->active) checked @endif name="active" value="1">
                            {!! Form::label('active', trans('lang.active'), ['class' => 'col-8 control-label mt-2']) !!}

                        </label>
                    </div>
                    <div class="form-group row mb-0 ">
                        <!-- 'Boolean closed Field' -->
                        <label class="col-10 mb-0  form-check-inline">
                            <input type="checkbox" @if($supermarket->closed) checked @endif  name="closed" value="1">
                            {!! Form::label('closed', trans('lang.closed'), ['class' => 'col-8 control-label mt-2']) !!}

                        </label>
                    </div>
                    
                    <div class="form-group row mb-0 ">
                        <label class="col-10 mb-0  form-check-inline">
                            <input type="checkbox" @if($supermarket->featured) checked @endif name="featured" value="1">
                            {!! Form::label('featured', trans('lang.featured'), ['class' => 'col-8 control-label mt-2']) !!}

                        </label>
                    </div>
                    <div class="form-group row ">
                        <!-- 'Boolean available_for_delivery Field' -->
                        <label class="col-12 form-check-inline">
                            <input type="checkbox" @if($supermarket->available_for_delivery) checked @endif  name="available_for_delivery" value="1">
                            {!! Form::label('available_for_delivery', trans('lang.available_for_delivery'), ['class' => 'col-8 control-label mt-2']) !!}

                        </label>
                    </div>
                
                    <div class="form-group row mt-1">
                        <!-- Description Field -->
                            {!! Form::label('description', trans('lang.description'), ['class' => 'col-12 control-label ']) !!}
                            <div class="col-11">

                                {!! Form::textarea('description', null, ['class' => 'form-control', 'placeholder' => trans('lang.description_placeholder'), 'rows' => 2, 'cols' => 2]) !!}
                                <div class="form-text text-muted">{{ trans('lang.description_help') }}
                                </div>
                    </div></div>
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
                        {!! Form::label('admin_commission', trans('lang.commission'), ['class' => 'col-12 control-label']) !!}
                        <div class="col-9">
                            {!! Form::text('admin_commission', $supermarket->admin_commission == null ? '10' : $supermarket->admin_commission, ['class' => 'form-control', 'placeholder' => trans('lang.commission_placeholder')]) !!}
                            <div class="form-text text-muted">
                                {{ trans('lang.commission_help') }}
                            </div>
                        </div>
                    </div>

                    <!-- default_tax Field -->
                    <div class="form-group row ">
                        {!! Form::label('default_tax', trans('lang.default_tax'), ['class' => 'col-12 control-label ']) !!}
                        <div class="col-9">
                            {!! Form::number('default_tax', null, ['class' => 'form-control', 'step' => 'any', 'placeholder' => trans('lang.default_tax_placeholder')]) !!}
                            <div class="form-text text-muted">
                                {{ trans('lang.default_tax_help') }}
                            </div>
                        </div>
                    </div>

                    <!-- Image Field -->
                    <div class="form-group row">
                        {!! Form::label('image', trans('lang.logo'), ['class' => 'col-12 control-label ']) !!}
                        <div class="col-9">
                            <div style="width: 100%" class="dropzone image" id="image" data-field="image">
                                <input type="hidden" name="image">
                            </div>
                            <a href="#loadMediaModal" data-dropzone="image" data-toggle="modal"
                                data-target="#mediaModal"
                                class="btn btn-outline-{{ setting('theme_color', 'primary') }} btn-sm float-right mt-1">{{ trans('lang.media_select') }}</a>
                            <div class="form-text text-muted w-50">
                                {{ trans('lang.image_help') }}
                            </div>
                        </div>
                    </div>
                    @prepend('scripts')
                        <script type="text/javascript">
                            var var15671147011688676454ble = '';
                            @if (isset($supermarket) && $supermarket->hasMedia('image'))
                                var15671147011688676454ble = {
                                name: "{!! $supermarket->getFirstMedia('image')->name !!}",
                                size: "{!! $supermarket->getFirstMedia('image')->size !!}",
                                type: "{!! $supermarket->getFirstMedia('image')->mime_type !!}",
                                collection_name: "{!! $supermarket->getFirstMedia('image')->collection_name !!}"
                                };
                            @endif
                            var dz_var15671147011688676454ble = $(".dropzone.image").dropzone({
                                url: "{!! url('uploads/store') !!}",
                                addRemoveLinks: true,
                                maxFiles: 1,
                                init: function() {
                                    @if (isset($supermarket) && $supermarket->hasMedia('image'))
                                        dzInit(this, var15671147011688676454ble, '{!! url($supermarket->getFirstMediaUrl('image', 'thumb')) !!}')
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
                                        'image', '{!! isset($supermarket) ? $supermarket->id : 0 !!}', '{!! url('uplaods/clear') !!}',
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
                            const deliverBoysElement = document.getElementById('drivers[]');
                            const deliverBoysGroupElement = document.getElementById('restaurant-body-drivers');
                        </script>
                        {{-- End events of private drivers --}}
                    @endprepend
                    <div class="row">
                        <div class="col-12 col-md-11 mt-4 pt-2">
                            <!-- Information Field -->
                            <div class="form-group row ">
                                {!! Form::label('information', trans('lang.information'), ['class' => 'col-12 control-label ']) !!}
                                <div class="col-12">
                                    {!! Form::textarea('information', null, ['class' => 'form-control', 'placeholder' => trans('lang.information_placeholder')]) !!}
                                    <div class="form-text text-muted">{{ trans('lang.information_help') }}
                                    </div>
                                </div>
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
                        {{ trans('lang.save') }} {{ trans('lang.supermarket') }}</button>
                    <a href="{!! route('operations.supermarkets.index') !!}" class="btn btn-default"><i class="fa fa-undo"></i>
                        {{ trans('lang.cancel') }}</a>
                </div>

            </div>
