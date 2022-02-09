

<div style="flex: 50%;max-width: 100%;padding: 0 4px;" class="column">

<div class="row">
    <div class="col col-md-12" style="width: 100%; border-collapse: collapse; overflow-x:auto;">
        <table class="table">
            <thead>
              <tr>
                <th scope="col">name</th>
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
                      <div class="col col-md-6 m-1">
                        <input type="number" class="form-control" id="quantity_new_{{$orderFoods->id}}" value="{{$orderFoods->quantity}}" onkeyup="changeQuantity({{$orderFoods->quantity}},{{$orderFoods->price}},{{$orderFoods->id}})" style="width: 114px;">
                      </div>
                      <div class="col col-md-6 m-1">
                        <button id="btn_update_{{$orderFoods->id}}" type="submit" onclick="updateQuantity({{$orderFoods->quantity}},{{$orderFoods->price}},{{$orderFoods->id}})" class="btn btn-primary" style="border-color: #33456b; background-color: #33456b;">
                          update <span id="waiting_{{$orderFoods->id}}"> <i class="fa fa-edit"></i></span>
                        </button>
                      </div>
                    </div>
                  </td>
                  <td>
                      @foreach ($orderFoods->extras as $extra)
                      {!! Form::open(['route' => ['orders.remove-extra', $orderFoods->id], 'method' => 'HEAD']) !!}
                        <input type="hidden" name="food_order_id" value="{{$extra->pivot->food_order_id}}">
                        <input type="hidden" name="extra_id" value="{{$extra->pivot->extra_id}}">
                        <button type="submit"  class="btn btn-outline-dark mb-2">
                            {{$extra->name}} <a><i class="fa fa-trash text-danger"></i></a>
                        </button>
                      {!! Form::close() !!}
                      @endforeach
                      {!! Form::open(['route' => ['orders.add-extra', $orderFoods->id], 'method' => 'HEAD']) !!}
                      <div class="row">
                        <div class="col col-md-6 m-1">
                          <select name="extraId" id="extra" class="select2 form-control">
                            @foreach ($orderFoods->food->extras as $extra)
                            @if (!$orderFoods->foodOrderExtra->pluck('extra_id')->contains($extra->id))
                              <option value="{{ $extra->id }}">{{ $extra->name}}</option>
                            @endif
                            @endforeach
                          </select>
                        </div>
                        <div class="col col-md-6 m-1">
                          <button style="background-color: #3c4b71; border-color: #3c4b71;" id="addExtra" class="btn btn-primary">Add Extra <i class="fa fa-plus"></i></button>
                        </div>
                      </div>
                      {!! Form::close() !!}
                    </td>
                  <td>
                    {{-- {!! Form::open(['route' => ['foodorders', $orderFoods->id], 'method' => 'delete']) !!}
                      {!! Form::button('<i class="fa fa-trash"></i>', [
                      'type' => 'submit',
                      'class' => 'btn btn-link text-danger',
                      'onclick' => "return confirm('Are you sure?')"
                      ]) !!}
                    {!! Form::close() !!}
                    <button type="submit" class="btn btn-danger"><i class="fa fa-trash"></i></button></td> --}}
                </tr>
                @endforeach
            </tbody>
          </table>
    </div>
</div>
</div>

<script>
  function changeQuantity(oldQuantity ,oldPrice ,orderFoodId) {
    console.log("hi");
    var new_quantity = $('#quantity_new_'+orderFoodId).val()
    var new_price = (oldPrice * new_quantity) / oldQuantity
    $('#order_foor_price_'+orderFoodId).val(new_price.toFixed(2))

  }
  function updateQuantity(oldQuantity ,oldPrice ,orderFoodId) {
    var new_quantity = $('#quantity_new_'+orderFoodId).val()
    var new_price = (oldPrice * new_quantity) / oldQuantity
    $('#btn_update_'+orderFoodId).html('update <i class="fa fa-spinner fa-spin"></i>');
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
        console.log(response);
        $('#btn_update_'+orderFoodId).html('update <i class="fa fa-edit"></i>');
        $('#btn_update_'+orderFoodId).prop('disabled', false);
      }
    });
  }
  $(document).ready(function () {
    
  });
</script>