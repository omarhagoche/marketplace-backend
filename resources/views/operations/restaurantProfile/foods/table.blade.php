
@push('css_lib')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.4/css/dataTables.bootstrap4.min.css">
@endpush
<div style="overflow-x:auto;">
    <table id="example" class="table table-striped table-bordered" style="width:100%">
        <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th style="width: 51.889px;">price</th>
                <th style="width: 122.01px;">discount price</th>
                <th style="width: 120.587px;">package count</th>
                <th>category_id</th>
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
                <td>
                    <div style="visibility: hidden; font-size: 2px;">{{$food->package_items_count}}</div>
                    <input type="text" value="{{$food->package_items_count}}"id="package_items_count{{$food->id}}" class="form-control">
                </td>
                <td>
                    <div style="visibility: hidden; font-size: 2px;">{{$food->category}}</div>
                    <div class="form-group">
                        {!! Form::select('category_id', $category, null, ['id'=>'category_id'.$food->id,'class' => 'select2 form-control']) !!}
                    </div>
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
@push('scripts')
<script src="https://cdn.datatables.net/1.11.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.4/js/dataTables.bootstrap4.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#example').DataTable();
        } );
        function editBasicInto(id){
            console.log($('#available'+id).val())
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
                }
            });
            $('#button-'+id).html(" <i class='fa fa-edit'></i>");
            $('#button-'+id).prop('disabled', false);
        }
        
    </script>
@endpush