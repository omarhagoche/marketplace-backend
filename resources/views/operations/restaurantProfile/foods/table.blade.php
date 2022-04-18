
@push('css_lib')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.4/css/dataTables.bootstrap4.min.css">
@endpush
<div style="overflow-x:auto;" class="scroll-inner">
    <table id="example" class="table table-striped table-bordered" style="width:100%">
        <thead>
            {{-- <tr id="search-fields">
                <th colspan="1"><span>#</span></th>
                <th colspan="2"><span>name</span></th>
                <th colspan="2"><span>price</span></th>
                <th><span>discount price</span></th>
                <th><span>package count</span></th>
                <th style="width: 136px;"><span hidden>category</span>{!! Form::select('category_id', $category,null, ['id'=>'search_cat','class' => 'select2 form-control']) !!}</th> 
                <th hidden>categoryname</th>
                <th hidden>available</th>
                <th hidden>update</th>
                <th hidden>actions</th> 
            </tr> --}}
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>price</th>
                <th>discount price</th>
                {{-- <th>package count</th> --}}
                <th>category</th>
                <th hidden>categoryname</th>
                <th>available</th>
                <th>update</th>
                <th>actions</th>
            </tr>
            
        </thead>

        <tbody>
            @foreach ($foods as $food)
            <tr>
                <td>{{$food->id}}</td>
                <td>
                    <div style="visibility: hidden; font-size: 2px;">{{$food->name}}</div>
                    <input style="min-width:193px;" type="text" value="{{$food->name}}" id="name{{$food->id}}" class="form-control">
                </td>
                <td>
                    <div style="visibility: hidden; font-size: 2px;">{{$food->price}}</div>
                    <input type="text" value="{{$food->price}}" id="price{{$food->id}}" class="form-control">
                </td>
                <td>
                    <div style="visibility: hidden; font-size: 2px;">{{$food->discount_price}}</div>
                    <input type="text" value="{{$food->discount_price}}" id="discount_price{{$food->id}}" class="form-control">
                </td>
                {{-- <td>
                    <div style="visibility: hidden; font-size: 2px;">{{$food->package_items_count}}</div>
                    <input type="text" value="{{$food->package_items_count}}"id="package_items_count{{$food->id}}" class="form-control">
                </td> --}}
                <td hidden>
                    <div>{{$food->category->name}}</div>
                </td>
                <td>
                    {!! Form::select('category_id', $category, $food->category_id, ['id'=>'category_id'.$food->id,'class' => 'select2 form-control']) !!}

                </td>
                <td>
                    <div class="form-group checkbox icheck">
                        <label class="col-9 ml-2 form-check-inline">
                            {!! Form::hidden('available', 0) !!}
                            {!! Form::checkbox('available', 1, $food->available,['id' => 'available'.$food->id]) !!}
                        </label>
                    </div>
                </td>
                <td style="text-align: center;">
                    <button class='btn btn-primary' onclick="editBasicInto({{$food->id}})" id="button-{{$food->id}}">
                          <i class="fa fa-edit"></i>
                    </button>
                </td>
                <td>
                    @include('operations.restaurantProfile.foods.table-actions',compact('food'))
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

@push('css_lib')
<style>
    .scroll-inner::-webkit-scrollbar {
        width: 10px;
    }
    .scroll-inner::-webkit-scrollbar:horizontal {
      height: 10px;
    }
    .scroll-inner::-webkit-scrollbar-track {
      background-color: rgba(204, 204, 204, 0.3);
    }
    .scroll-inner::-webkit-scrollbar-thumb {
      border-radius: 10px;
      background: rgba(204, 204, 204, 0.5);
      box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.5);
    }
</style>

@endpush

@push('scripts')
<script src="https://cdn.datatables.net/1.11.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.4/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

<script>
$(document).ready(function() {
    // Setup - add a text input to each footer cell
    $('#search-fields th span').each( function () {
        var title = $(this).text();
        if(title != "category")
        $(this).html( '<input style="width: 100%;" class="form-control" type="text" placeholder="'+title+'" />' );
    
    } );

    // DataTable
    var table = $('#example').DataTable({
        dom: 'Bfrtip',
        buttons: [
            { extend: "excel", className: "btn btn-success add-excel",text:"Export " }
        ],
        initComplete: function () {
            // Apply the search
            this.api().columns().every( function () {
                var that = this;
                $($( '#search-fields input')[that[0][0]]).on( 'keyup change clear', function () {
                    if ( that.search() !== this.value ) {
                        that.search( this.value ).draw();
                    }
                });
            });
        }
    });

    $('.add-excel').append(" <i class='fa fa-file-excel-o'></i>");

    $("#search_cat").on("change", function() {
        $("#example").DataTable()
        .columns(5)
        .search($("#search_cat option:selected" ).text())
        .draw();
    });
});
  
    function editBasicInto(id) {

        $('#button-'+id).html(" <i class='fa fa-spinner fa-spin'></i>");
        $('#button-'+id).prop('disabled', true);

        $.ajax({
            type: "POST",
            url: "{{route('operations.restaurant.food.update')}}",
            data: {
                "id" : id,
                "name" : $('#name'+id).val(),
                "price" : $('#price'+id).val(),
                "discount_price" : $('#discount_price'+id).val(),
                "category_id" : $('#category_id'+id).val(),
                "package_items_count" : $('#package_items_count'+id).val(),
                "available" : $('#available'+id).val(),
                _token: '{{csrf_token()}}'
            },
            dataType: "json",
            success: function (response) {
                $('#button-'+id).html(" <i class='fa fa-edit'></i>");
                $('#button-'+id).prop('disabled', false);
                swal("Good job!", "Food Updated Successfully!", "success");
            },
            error : function(XMLHttpRequest, textStatus, errorThrown) {
                $('#button-'+id).html(" <i class='fa fa-edit'></i>");
                $('#button-'+id).prop('disabled', false);
                $('#button-'+id).html(" <i class='fa fa-edit'></i>");
                $('#button-'+id).prop('disabled', false);
                swal("Error!", textStatus+" | "+errorThrown, "error");
            }
        });    
    }
        
</script>
@endpush

