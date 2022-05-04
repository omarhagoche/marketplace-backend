@extends('operations.layouts.app')

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0 text-dark">{{trans('lang.drivers_map')}}<small class="ml-3 mr-3"></small></h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{url('operations/')}}"><i class="fa fa-dashboard"></i> {{trans('lang.dashboard')}}</a></li>
          <li class="breadcrumb-itema ctive"><a href="{!! route('operations.drivers.index') !!}">{{trans('lang.driver_plural')}}</a>
          </li>
        </ol>
      </div><!-- /.col -->
    </div><!-- /.row -->
  </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->
<div class="content">
  <div class="card">
    <div class="card-body">
      <div class="row">
        
        <div class="col-12">
          <div class="row">
            <div class="col-lg-3 col-6">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3 id="total-drivers">0</h3>
                        <p>Total drivers</p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-car"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-danger">
                    <div class="inner">
                        <h3 id="unavailable-drivers">0</h3>
                        <p>Unavailable drivers</p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-car"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3 id="free-drivers">0</h3>
                        <p>Free drivers</p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-car"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-primary">
                    <div class="inner">
                        <h3 id="busy-drivers">0</h3>
                        <p>Busy drivers</p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-car"></i>
                    </div>
                </div>
            </div>
          </div>
        </div>
        
        <div class="col-4 mb-4">
            <input type="text" name="" id="search-input" class="form-control">
        </div>
        <div class="col-2 mb-4">
            {!! Form::select('searchType', [""=>"Select Serch Type"
            ,"name" =>"Name",
            "phone_number"  =>"Phone Number"
            ], null,["class"=>"select2 form-control","id" => "search-type"]) !!}
        </div>
        <div class="col-2 mb-4">
            {!! Form::submit("Search", ["id" => "search-btn", "class" => "btn btn-primary"]) !!}
        </div>
        <div class="col-6">
            <div class="card">
                <div class="card-header no-border">
                    <h3 class="card-title">Orders</h3>
                    <div class="card-tools">
                        <a href="{{ route("operations.orders.index")}}" class="btn btn-tool btn-sm"><i class="fa fa-bars"></i> </a>
                    </div>
                </div>
                <div class="card-body p-0">
                    <table class="table table-striped table-valign-middle">
                        <thead>
                        <tr>
                            <th>order no</th>
                            <th>driver id</th>
                            <th>driver name</th>
                            <th>driver phone</th>
                            <th>total</th>
                            <th>find driver</th>
                        </tr>
                        </thead>
                        <tbody id="orders">

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-6">
            <div id="map" style="width: 100%; height: 500px;"></div>
        </div>
        

        <!-- Back Field -->
        <div class="form-group col-12 text-right">
          <a href="{!! route('operations.drivers.index') !!}" class="btn btn-default"><i class="fa fa-undo"></i> {{trans('lang.back')}}</a>
        </div>
      </div>
      <div class="clearfix"></div>

    </div>
  </div>
</div>

@section('extra-js')

<script src="https://maps.google.com/maps/api/js?key={{ setting('google_maps_key',"AIzaSyAT07iMlfZ9bJt1gmGj9KhJDLFY8srI6dA") }}" type="text/javascript"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js" type="text/javascript"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

<script type="text/javascript">
    function searchDriverById(driverId){
        console.log(driverId)
        db.collection('drivers').where("id", "==", `${driverId}`).get().then((querySnapshot) => {
            querySnapshot.forEach((doc) => {
                console.log(doc.data())
                lats = doc.data().latitude;
                longs = doc.data().longitude;
                map.setCenter(new google.maps.LatLng(lats, longs));
                map.setZoom(20);
            });

        }).catch(e => {
            console.log(e);
            alert(e.message);
        });        
    }
    $(document).ready(function () {

        db.collection('current_orders').get().then((querySnapshot) => {
            querySnapshot.forEach((doc) => {
                if(doc.data().order_status_id != 80 && doc.data().order_status_id != 110 && doc.data().order_status_id != 100  && doc.data().order_status_id != 20 && doc.data().order_status_id != 105 && doc.data().order_status_id != 120 && doc.data().order_status_id != 130 && doc.data().order_status_id != 140 )
                {
                    db.collection('drivers').where("id", "==", doc.data().driver_id).get().then((querySnapshot) => {
                        querySnapshot.forEach((docorder) => {
                            $("#orders").append(`<tr>
                            <td><a>${doc.data().id}</a></td>
                            <td>${doc.data().driver_id}</td>
                            <td>${docorder.data().name}</td>
                            <td>${docorder.data().phone_number}</td>
                            <td>${doc.data().total}</td>
                            <td><button class="btn btn-primary" onclick="searchDriverById(${doc.data().driver_id})"> <i class="fa fa-search"></i></button></td>
                            <tr>`);
                        });
                    });
                }
            });
        });
        $("#search-btn").click(function () { 
            if($("#search-type").val() == "" || $("#search-input").val() == ""){
                swal("Error!", "fields can't be empty", "error");
            }
            db.collection('drivers').where($("#search-type").val(), "==", $("#search-input").val()).get().then((querySnapshot) => {
                db_driver = [];
                querySnapshot.forEach((doc) => {
                    db_driver.push(doc.data());
                });
                if(db_driver[0].available == true) {
                lats = db_driver[0].latitude;
                longs = db_driver[0].longitude;
                map.setCenter(new google.maps.LatLng(lats, longs));
                map.setZoom(20);
                }
            }).catch(e => {
                swal("Error!", "not exist", "error");
            });        
        });
    });
    var map = new google.maps.Map(document.getElementById('map'), {
        zoom: 11,
        center: new google.maps.LatLng(32.8078757,13.2627382),
        mapTypeId: google.maps.MapTypeId.ROADMAP
    });

    var infowindow = new google.maps.InfoWindow();

    var markers = [];

    function getDriverColorStatus(index){
        const d = db_drivers[index];
        if (!d.available) return '#f44336';
        return (d.working_on_order && '#007bff') || '#28A745';
    }

        // Adds a marker to the map and push to the array.
    function addMarker(lat, long,index) {
        const colorStatus = {
            
        };
        const marker = new google.maps.Marker({
            position: new google.maps.LatLng(lat, long),
            icon: {
            path: google.maps.SymbolPath.CIRCLE,
            scale: 6,
            fillColor: getDriverColorStatus(index),
            fillOpacity: 0.9,
            strokeWeight: 0.2
            },
            map: map,
        });

        google.maps.event.addListener(
            marker,
            "click",
            (function(marker, index) {
            return function() {
                infowindow.setContent(`
                    ${db_drivers[index].id}
                    <div>${db_drivers[index].name}</div>
                    <h6>${db_drivers[index].phone_number}</h6>
                    <div>Last access</div>
                    <h6>${moment(db_drivers[index].last_access).format()}</h6>
                    <h6>${moment(db_drivers[index].last_access).fromNow()}</h6>
                `);
                infowindow.open(map, marker);
            };
            })(marker, index)
        );

        markers.push(marker);
    }

    function setMapOnAll(map) {
        for (let i = 0; i < markers.length; i++) {
            markers[i].setMap(map);
        }
    }
 
</script>


<script  >
    
    function map_set_driver(data){
        
        var marker, i;

        for (i = 0; i < data.length; i++) {  
            if(data[i].available){ // skip show unavailable drivers on map
                addMarker(data[i].latitude, data[i].longitude,i);
            }
        }
    }

    var db_drivers = []; 
    var db = firebase.firestore();

    function getDriversFromFirebaseAndSetThemOnMap(){
        db.collection('drivers').get().then((querySnapshot) => {
            db_drivers = [];
            querySnapshot.forEach((doc) => {
                db_drivers.push(doc.data());
            });
            if(db_drivers.length){
                setMapOnAll(null);
                map_set_driver(db_drivers);
                $("#total-drivers").text(db_drivers.length || 0);
                $("#unavailable-drivers").text(db_drivers && db_drivers.filter(i => !i.available).length || 0);
                $("#free-drivers").text(db_drivers && db_drivers.filter(i => i.available && !i.working_on_order).length || 0);
                $("#busy-drivers").text(db_drivers && db_drivers.filter(i => i.available && i.working_on_order).length || 0);
            }
        }).catch(e => {
            console.log(e);
            alert(e.message);
        });
        
        /* const observer = db.collection('drivers').onSnapshot(snapshot => {
            db_drivers = [];
            snapshot.forEach((doc) => {
                db_drivers.push(doc.data());
            });
            if(db_drivers.length){
                setMapOnAll(null);
                map_set_driver(db_drivers);
                $("#total-drivers").text(db_drivers.length || 0);
                $("#unavailable-drivers").text(db_drivers && db_drivers.filter(i => !i.available).length || 0);
                $("#free-drivers").text(db_drivers && db_drivers.filter(i => i.available && !i.working_on_order).length || 0);
                $("#busy-drivers").text(db_drivers && db_drivers.filter(i => i.available && i.working_on_order).length || 0);
            }
        }, e => {
            console.log(e);
            alert(e.message);
        }) */
    }

    getDriversFromFirebaseAndSetThemOnMap();
    setInterval(() => {
        getDriversFromFirebaseAndSetThemOnMap()
    }, 10000);

</script>

@endsection


@endsection