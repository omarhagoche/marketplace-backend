@if($customFields)
    <h5 class="col-12 pb-4">{!! trans('lang.main_fields') !!}</h5>
@endif
<div class="col-md-5 col-12">
    @if (isset($isCreate))
       <!-- Name Field -->

    <div class="form-group row ">
        {!! Form::label('email', trans("lang.email"), ['class' => 'col-3 control-label text-right']) !!}
        <div class="col-9">
            {!! Form::text('email', null,  ['class' => 'form-control','placeholder'=>  trans("lang.email")]) !!}
            <div class="form-text text-muted">
                {{ trans("lang.email") }}
            </div>
        </div>
    </div> 
    @endif
    


  <!-- Name Field -->
  <div class="form-group row ">
    {!! Form::label('name', trans("lang.restaurant_name"), ['class' => 'col-3 control-label text-right']) !!}
    <div class="col-9">
        {!! Form::text('name', null,  ['class' => 'form-control','placeholder'=>  trans("lang.restaurant_name_placeholder")]) !!}
        <div class="form-text text-muted">
            {{ trans("lang.restaurant_name_help") }}
        </div>
    </div>
</div>

    <!-- Phone Field -->
    <div class="form-group row ">
        {!! Form::label('phone', trans("lang.restaurant_phone"), ['class' => 'col-3 control-label text-right']) !!}
        <div class="col-9">
            {!! Form::text('phone', null,  ['class' => 'form-control','placeholder'=>  trans("lang.restaurant_phone_placeholder")]) !!}
            <div class="form-text text-muted">
                {{ trans("lang.restaurant_phone_help") }}
            </div>
        </div>
    </div>

    <!-- Mobile Field -->
    <div class="form-group row ">
        {!! Form::label('mobile', trans("lang.restaurant_mobile"), ['class' => 'col-3 control-label text-right']) !!}
        <div class="col-9">
            {!! Form::text('mobile', null,  ['class' => 'form-control','placeholder'=>  trans("lang.restaurant_mobile_placeholder")]) !!}
            <div class="form-text text-muted">
                {{ trans("lang.restaurant_mobile_help") }}
            </div>
        </div>
    </div>

    <!-- Address Field -->
    <div class="form-group row ">
        {!! Form::label('address', trans("lang.restaurant_address"), ['class' => 'col-3 control-label text-right']) !!}
        <div class="col-9">
            {!! Form::text('address', null,  ['class' => 'form-control','placeholder'=>  trans("lang.restaurant_address_placeholder")]) !!}
            <div class="form-text text-muted">
                {{ trans("lang.restaurant_address_help") }}
            </div>
        </div>
    </div>

    <!-- Address Field -->
    <div class="form-group row ">
        {!! Form::label('email', trans("lang.email"), ['class' => 'col-3 control-label text-right']) !!}
        <div class="col-9">
            {!! Form::text('email', null,  ['class' => 'form-control','placeholder'=>  trans("lang.email_placeholder")]) !!}
            <div class="form-text text-muted">
                {{ trans("lang.email_help") }}
            </div>
        </div>
    </div>

  <!-- Address Field -->
    <div class="form-group row ">
        {!! Form::label('open_at', trans("lang.open_at"), ['class' => 'col-3 control-label text-right']) !!}
        <div class="col-9">
            {!! Form::time('open_at', '10:30',  ['class' => 'form-control','placeholder'=>  trans("lang.open_at")]) !!}
            <div class="form-text text-muted">
                {{ trans("lang.open_at_help") }}
            </div>
        </div>
    </div>
    <div class="form-group row ">
        {!! Form::label('close_at', trans("lang.close_at"), ['class' => 'col-3 control-label text-right']) !!}
        <div class="col-9">
            {!! Form::time('close_at', '23:30',  ['class' => 'form-control','placeholder'=>  trans("lang.close_at")]) !!}
            <div class="form-text text-muted">
                {{ trans("lang.close_at_help") }}
            </div>
        </div>
    </div>




    <!-- Latitude Field -->
    <div class="form-group row ">
        {!! Form::label('latitude', trans("lang.restaurant_latitude"), ['class' => 'col-3 control-label text-right']) !!}
        <div class="col-9">
            {!! Form::text('latitude', null,  ['class' => 'form-control','placeholder'=>  trans("lang.restaurant_latitude_placeholder")]) !!}
            <div class="form-text text-muted">
                {{ trans("lang.restaurant_latitude_help") }}
            </div>
        </div>
    </div>

    <!-- Longitude Field -->
    <div class="form-group row ">
        {!! Form::label('longitude', trans("lang.restaurant_longitude"), ['class' => 'col-3 control-label text-right']) !!}
        <div class="col-9">
            {!! Form::text('longitude', null,  ['class' => 'form-control','placeholder'=>  trans("lang.restaurant_longitude_placeholder")]) !!}
            <div class="form-text text-muted">
                {{ trans("lang.restaurant_longitude_help") }}
            </div>
        </div>
    </div>

</div>
<div class="col-md-7 col-12">

    <!-- Image Field -->
    <div class="form-group row">
        {!! Form::label('image', trans("lang.restaurant_image"), ['class' => 'col-3 control-label text-right']) !!}
        <div class="col-9">
            <div style="width: 100%" class="dropzone image" id="image" data-field="image">
                <input type="hidden" name="image">
            </div>
            <a href="#loadMediaModal" data-dropzone="image" data-toggle="modal" data-target="#mediaModal" class="btn btn-outline-{{setting('theme_color','primary')}} btn-sm float-right mt-1">{{ trans('lang.media_select')}}</a>
            <div class="form-text text-muted w-50">
                {{ trans("lang.restaurant_image_help") }}
            </div>
        </div>
    </div>
    <div class="form-group row">
        {!! Form::label('map', trans("lang.map"), ['class' => 'col-3 control-label text-right']) !!}
        <div class="col-9" >
            <div  id="map" style="width: 100%; height: 300px;"></div>
        </div>
    </div>
    @prepend('scripts')
        <script type="text/javascript">
            var var15671147011688676454ble = '';
            @if(isset($restaurant) && $restaurant->hasMedia('image'))
                var15671147011688676454ble = {
                name: "{!! $restaurant->getFirstMedia('image')->name !!}",
                size: "{!! $restaurant->getFirstMedia('image')->size !!}",
                type: "{!! $restaurant->getFirstMedia('image')->mime_type !!}",
                collection_name: "{!! $restaurant->getFirstMedia('image')->collection_name !!}"
            };
                    @endif
            var dz_var15671147011688676454ble = $(".dropzone.image").dropzone({
                    url: "{!!url('uploads/store')!!}",
                    addRemoveLinks: true,
                    maxFiles: 1,
                    init: function () {
                        @if(isset($restaurant) && $restaurant->hasMedia('image'))
                        dzInit(this, var15671147011688676454ble, '{!! url($restaurant->getFirstMediaUrl('image','thumb')) !!}')
                        @endif
                    },
                    accept: function (file, done) {
                        dzAccept(file, done, this.element, "{!!config('medialibrary.icons_folder')!!}");
                    },
                    sending: function (file, xhr, formData) {
                        dzSending(this, file, formData, '{!! csrf_token() !!}');
                    },
                    maxfilesexceeded: function (file) {
                        dz_var15671147011688676454ble[0].mockFile = '';
                        dzMaxfile(this, file);
                    },
                    complete: function (file) {
                        dzComplete(this, file, var15671147011688676454ble, dz_var15671147011688676454ble[0].mockFile);
                        dz_var15671147011688676454ble[0].mockFile = file;
                    },
                    removedfile: function (file) {
                        dzRemoveFile(
                            file, var15671147011688676454ble, '{!! url("restaurants/remove-media") !!}',
                            'image', '{!! isset($restaurant) ? $restaurant->id : 0 !!}', '{!! url("uplaods/clear") !!}', '{!! csrf_token() !!}'
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
                        elementToRemoveName.setAttribute('name','delivery_fee')
                }else {
                        elementToHide.style.display = "none"
                        elementToRemoveName.removeAttribute('name');
                }
            }
            hideAndRemoveName(el.value);

            el.onchange = function (event) {
                hideAndRemoveName(el.value)
            }
        </script>

        {{-- Start events of private drivers to show/hide delivery boys depends on status  --}}
        <script>
            const prviateDriversElement = $('.private-drivers input');
            const deliverBoysElement = document.getElementById('drivers[]');
            const deliverBoysGroupElement = document.getElementById('restaurant-body-drivers');

            function checkPrivateDriversStatus(value) {
                if (value) {
                        deliverBoysGroupElement.style.display = "";
                        deliverBoysElement.setAttribute('name','drivers[]');
                }else {
                        deliverBoysGroupElement.style.display = "none";
                        deliverBoysElement.removeAttribute('name');
                }
            } 

            $(prviateDriversElement).on('ifChanged', function(event){
                $(this).iCheck('update'); // apply input changes, which were done outside the plugin
                checkPrivateDriversStatus(event.target.checked);
            });
            $(prviateDriversElement).trigger('ifChanged')
        </script>
        {{-- End events of private drivers --}}
@endprepend

</div>

<!-- Submit Field -->
<div class="form-group col-12 text-right">
    <button type="submit" class="btn btn-{{setting('theme_color')}}"><i class="fa fa-save"></i> {{trans('lang.save')}} {{trans('lang.restaurant')}}</button>
    <a href="{!! route('restaurants.index') !!}" class="btn btn-default"><i class="fa fa-undo"></i> {{trans('lang.cancel')}}</a>
</div>



@section('extra-js')

<script src="https://maps.google.com/maps/api/js?key={{ setting('google_maps_key',"AIzaSyAT07iMlfZ9bJt1gmGj9KhJDLFY8srI6dA") }}" type="text/javascript"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js" type="text/javascript"></script>

<script type="text/javascript">
window.onload = function() {

var latlng = new google.maps.LatLng(32.8836618,13.1844355); //Set the default location of map

var map = new google.maps.Map(document.getElementById('map'), {

    center: latlng,

    zoom: 10, //The zoom value for map

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

$(document).ready(function () {
    $("#latitude").keyup(function () { 
        var latlng = new google.maps.LatLng($('#latitude').val(), $('#longitude').val());
        map.setCenter(new google.maps.LatLng($('#latitude').val(), $('#longitude').val()));
        marker.setPosition(latlng);
    });
    $("#longitude").keyup(function () { 
        var latlng = new google.maps.LatLng($('#latitude').val(), $('#longitude').val());
        map.setCenter(new google.maps.LatLng($('#latitude').val(), $('#longitude').val()));
        marker.setPosition(latlng);
    });
});
};
</script>
@endsection



