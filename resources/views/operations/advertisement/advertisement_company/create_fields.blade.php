@if($customFields)
    <h5 class="col-12 pb-4">{!! trans('lang.main_fields') !!}</h5>
@endif
<div class="col-md-5 col-12">
    @if (isset($isCreate))
 
    @endif
    


  <!-- name Field -->
  <div class="form-group row ">
    {!! Form::label('name', trans("lang.name"), ['class' => 'col-3 control-label text-right']) !!}
    <div class="col-9">
        {!! Form::text('name', null,  ['class' => 'form-control','placeholder'=>  trans("lang.name_placeholder")]) !!}
        <div class="form-text text-muted">
            {{ trans("lang.name") }}
        </div>
    </div>
</div>



  <!-- link Field -->
  <div class="form-group row ">
    {!! Form::label('link', trans("lang.link"), ['class' => 'col-3 control-label text-right']) !!}
    <div class="col-9">
        {!! Form::text('link', null,  ['class' => 'form-control','placeholder'=>  trans("lang.link")]) !!}
        <div class="form-text text-muted">
            {{ trans("lang.link") }}
        </div>
    </div>
</div>



 


  <!-- manager_user_id -->
<div class="form-group row ">
    {!! Form::label('manager_user_id', trans("lang.manager_user_id"),['class' => 'col-3 control-label text-right']) !!}
    <div class="col-9">
      {!! Form::select('manager_user_id', $manager_user_id, $manager_user_id_Selected, ['class' => 'select2 form-control' ]) !!}
      <div class="form-text text-muted">{{ trans("lang.manager_user_id") }}</div>
    </div>
  </div>
 

 

 




   




   

</div>
<div class="col-md-7 col-12">

    <!-- Image Field -->
    <div class="form-group row">
        {!! Form::label('logo', trans("lang.logo"), ['class' => 'col-3 control-label text-right']) !!}
        <div class="col-9">
            <div style="width: 100%" class="dropzone image" id="logo" data-field="logo">
                <input type="hidden" name="logo">
            </div>
            <a href="#loadMediaModal" data-dropzone="image" data-toggle="modal" data-target="#mediaModal" class="btn btn-outline-{{setting('theme_color','primary')}} btn-sm float-right mt-1">{{ trans('lang.media_select')}}</a>
            <div class="form-text text-muted w-50">
                {{ trans("lang.image_help") }}
            </div>
        </div>
    </div>
   
    @prepend('scripts')
        <script type="text/javascript">
            var var15671147011688676454ble = '';
            @if(isset($supermarket) && $supermarket->hasMedia('logo'))
                var15671147011688676454ble = {
                name: "{!! $supermarket->getFirstMedia('logo')->name !!}",
                size: "{!! $supermarket->getFirstMedia('logo')->size !!}",
                type: "{!! $supermarket->getFirstMedia('logo')->mime_type !!}",
                collection_name: "{!! $supermarket->getFirstMedia('logo')->collection_name !!}"
            };
                    @endif
            var dz_var15671147011688676454ble = $(".dropzone.image").dropzone({
                    url: "{!!url('uploads/store')!!}",
                    addRemoveLinks: true,
                    maxFiles: 1,
                    init: function () {
                        @if(isset($supermarket) && $supermarket->hasMedia('image'))
                        dzInit(this, var15671147011688676454ble, '{!! url($supermarket->getFirstMediaUrl('image','thumb')) !!}')
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
                            file, var15671147011688676454ble, '{!! url("supermarkets/remove-media") !!}',
                            'image', '{!! isset($supermarket) ? $supermarket->id : 0 !!}', '{!! url("uplaods/clear") !!}', '{!! csrf_token() !!}'
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
            const deliverBoysGroupElement = document.getElementById('supermarket-body-drivers');

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
    <button type="submit" class="btn btn-{{setting('theme_color')}}"><i class="fa fa-save"></i> {{trans('lang.save')}} {{trans('lang.advertisement_company')}}</button>
    <a href="{!! route('operations.supermarkets.index') !!}" class="btn btn-default"><i class="fa fa-undo"></i> {{trans('lang.cancel')}}</a>
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



