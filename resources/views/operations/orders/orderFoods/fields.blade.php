

<div style="flex: 50%;max-width: 100%;padding: 0 4px;" class="column">
  <div class="row">
    <label> Add Food To Order</label>
  </div>
  {!! Form::hidden('food_old_price', null, ['id' => 'food_old_price']) !!}
  {!! Form::open(['route' => ['foodOrders.store'], 'method' => 'post']) !!}
  <div class="row">
    <div class="col col-md-2">
      <label for="restaurant_foood"> foods</label>
      {!! Form::hidden('order_id', $orderId, ['id' => 'food_old_price']) !!}
    {!! Form::select('food_id', $restaurantFooods, null, ['class' => 'select2 form-control', 'id' => 'restaurant_foood']) !!}
  </div>
  <div class="col col-md-2">
    <label for="food_price">price</label>
    <div id="waiting_price"></div>
    {!! Form::text('price', null, ['class' => 'form-control','id' => 'food_price', 'readonly' => 'readonly']) !!}
  </div>
  <div class="col col-md-2">
    <label for="food_quantity">quantity</label>
    {!! Form::number('quantity', 1, ['class' => 'form-control', 'id' => 'food_quantity']) !!}
  </div>
  <div class="col col-md-1">
    <label for="add"></label>
    <button type="submit" class="form-control btn btn-primary"> Add Food 
      <i class="fa fa-plus"></i>
    </button>
  </div>
</div>
{!! Form::close() !!}
<hr>
<div class="row">
    <div class="col col-md-12" style="width: 100%; border-collapse: collapse; overflow-x:auto;">
        <table class="table">
            <thead>
              <tr>
                <th scope="col" style="width: 486px;">name</th>
                <th scope="col">price</th>
                <th scope="col">quantity</th>
                <th scope="col">extras</th>
                <th scope="col">delete</th>
              </tr>
            </thead>
            <tbody>
                @foreach ($orderFoods as $orderFoods)
                <tr>
                  <th scope="row">{{ $orderFoods->food->name}}</th>
                  <td style="width: 178px">
                    {!! Form::text('orderFoods_price', $orderFoods->price, ['class' => 'form-control', 'style' => 'width: 114px','disabled' => 'disabled','id'=> 'order_foor_price_'.$orderFoods->id]) !!}
                  </td>
                  <td style="width: 270px">
                    <div class="row">
                      <div class="col col-md-12 m-1">
                        <input type="number" class="form-control" min="1" oninput="validity.valid||(value='');"  id="quantity_new_{{$orderFoods->id}}" value="{{$orderFoods->quantity}}" onkeyup="changeQuantity({{$orderFoods->quantity}},{{$orderFoods->price}},{{$orderFoods->id}})" style="width: 114px; display: inline;">
                        <button id="btn_update_{{$orderFoods->id}}" type="submit" onclick="updateQuantity({{$orderFoods->quantity}},{{$orderFoods->price}},{{$orderFoods->id}})" class="btn btn-primary" style="border-color: #33456b; background-color: #33456b;">
                           <div id="waiting_{{$orderFoods->id}}"> <i class="fa fa-edit"></i></div>
                        </button>
                      </div>
                      
                    </div>
                  </td>
                  <td>
                      {!! Form::open(['route' => ['orders.add-extra', $orderFoods->id], 'method' => 'HEAD']) !!}
                        <div class="form-group row m-1">
                          <div class="col-8">
                              {!! Form::select('extras[]', $orderFoods->food->extras->pluck('name','id'), $orderFoods->extras, ['class' => 'select2 form-control' , 'multiple'=>'multiple']) !!}
                            </div>
                            <button style="background-color: #3c4b71; border-color: #3c4b71;"  id="addExtra" class="btn btn-primary"><i class="fa fa-edit"></i></button>
                        </div>
                      {!! Form::close() !!}
                  </td>
                  <td>
                    {!! Form::open(['route' => ['foodOrders.destroy', $orderFoods->id], 'method' => 'delete']) !!}
                      {!! Form::button('<i class="fa fa-trash" style="font-size: 33px;"></i>', [
                      'type' => 'submit',
                      'class' => 'btn btn-link text-danger',
                      'onclick' => "return confirm('Are you sure?')"
                      ]) !!}
                    {!! Form::close() !!}
                </tr>
                @endforeach
            </tbody>
          </table>
    </div>
</div>
</div>
@push('scripts')
    
<script>
  function changeQuantity(oldQuantity ,oldPrice ,orderFoodId) {
    var new_quantity = $('#quantity_new_'+orderFoodId).val()
    if(new_quantity == ''){
      return
    }
    var new_price = (oldPrice * new_quantity) / oldQuantity
    $('#order_foor_price_'+orderFoodId).val(new_price.toFixed(2))

  }
  function updateQuantity(oldQuantity ,oldPrice ,orderFoodId) {
    
    var new_quantity = $('#quantity_new_'+orderFoodId).val()
    if(new_quantity == "" || new_quantity <= 0) {
      alert("hi");
      return;
    }
    var new_price = (oldPrice * new_quantity) / oldQuantity
    $('#btn_update_'+orderFoodId).html('<i class="fa fa-spinner fa-spin"></i>');
    $('#btn_update_'+orderFoodId).prop('disabled', true);
    
    $.ajax({
      type: "post",
      url: "{{route('orders.food-update-quantity')}}",
      data: {
        "new_quantity" : new_quantity,
        "new_price"    : new_price.toFixed(2),
        "orderFoodId" : orderFoodId,
        "_token": "{{ csrf_token() }}",
      },
      dataType: "json",
      success: function (response) {
        $('#btn_update_'+orderFoodId).html('<i class="fa fa-edit"></i>');
        $('#btn_update_'+orderFoodId).prop('disabled', false);
      }
    });
  }

  $(document).ready(function () {
    
    $('select#restaurant_foood').change(function () { 
      var foodId = $(this).val();
      var url = "{{ route('foods.get-one', ":foodId") }}";
      url = url.replace(':foodId', foodId); 
      $('#food_price').val("");
      $("#food_price").hide();
      $("#waiting_price").html("<i class='fa fa-spinner fa-spin'></i>");
      $.ajax({
        type: "GET",
        url: url,
        dataType: "json",
        success: function (response) {
          console.log(response);
          $("#waiting_price").html("")
          $("#food_price").show()
          $('#food_price').val(response.price);
          $('#food_old_price').val(response.price);
        }
      });
      
    });

    $('#food_quantity').keyup(function () { 
      console.log($(this).val())
      if($('#food_price').length != 0) {
        console.log("hi");
        var new_quantity = $(this).val()
        var new_price = $('#food_old_price').val() * new_quantity
        $('#food_price').val(new_price.toFixed(2))
      }
    });
  });
</script>
@endpush