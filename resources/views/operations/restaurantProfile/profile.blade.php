<div class="card-header"> 
    <div class="col-12" style="text-align: center;"> 
        <img src="{{ $restaurant->media->first()?$restaurant->media->first()->getUrl():''}}" style="width: 100px;" alt="Avatar" class="avatar">
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
            {!! Form::text('longitude', null,  ['id' => 'longitude', 'class' => 'form-control','placeholder'=>  trans("lang.restaurant_longitude_placeholder")]) !!}
            <div class="form-text text-muted">
                {{ trans("lang.restaurant_longitude_help") }}
            </div>
        </div>
    </div>
    <div class="form-group row ">
        <div  id="map" style="width: 100%; height: 300px;"></div>
    </div>


</div>

@section('extra-js')

<script src="https://maps.google.com/maps/api/js?key={{ setting('google_maps_key',"AIzaSyAT07iMlfZ9bJt1gmGj9KhJDLFY8srI6dA") }}" type="text/javascript"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js" type="text/javascript"></script>

<script type="text/javascript">
window.onload = function() {

var latlng = new google.maps.LatLng({{$restaurant->latitude}},{{$restaurant->longitude}}); //Set the default location of map

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