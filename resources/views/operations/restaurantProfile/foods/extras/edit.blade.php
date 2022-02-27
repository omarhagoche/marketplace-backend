<div class="col-md-12">
    <table class="table">
      <thead>
        <tr>
          <th>name</th>
          <th>Extra Group</th>
          <th>price</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <tr id="addRow">
          <td class="col-xs-3">
            <input class="form-control addName" type="text" placeholder="Enter title" />
          </td>
          
          <td class="col-xs-3">
            {!! Form::select('extra_group', $extraGroup, null, ['class' => 'select2 form-control extra_group']) !!}
          </td>
          <td class="col-xs-5">
            <input class="form-control addPrice"  type="number" placeholder="Enter title" />
          </td>
          <td class="col-xs-1 text-center">
            <span class="addBtn">
                <i class="fa fa-plus"></i>
              </span>
          </td>
        </tr>
        @foreach ($food->extrasFood as $extraFood) 
        <tr>
            <td class="col-xs-3"><input id="name{{$extraFood->id}}" type="text" value="{{$extraFood->extra->name}}" name="name_extra[]" class="form-control editable" /></td>
            <td class="col-xs-3">{!! Form::select('group_extra[]', $extraGroup, $extraFood->extra->extra_group_id, ['id' => 'group_extra'.$extraFood->id,'class' => 'select2 form-control extra_group']) !!}</td>
            <td class="col-xs-3"><input id="price{{$extraFood->id}}" type="number" value="{{$extraFood->extra->price}}" name="price_extra[]" class="form-control editable" /></td>
            <td class="col-xs-1 text-center"><a  href="javascript:void(0)" onClick="EditRow({{$extraFood->id }},{{$extraFood->extra->id}})"> 
            <i class="fa fa-edit" aria-hidden="true"></a></td>
            <td class="col-xs-1 text-center"><a href="javascript:void(0)" onClick="deleteRow(this,{{$extraFood->extra->id}})"> 
            <i class="fa fa-trash-o" aria-hidden="true"></a></td>
        </tr>
        @endforeach
      </tbody>
    </table>
    </div>
@push('scripts')
<script>
  var name;
  var extraGroup;
  var price;


function deleteRow(trash,extraid) {
  console.log(extraid)
  $(trash).closest('tr').remove();
  var urlbase = '{{ route("operations.restaurant.foods.extra.delete", ":extraId") }}';
  urlbase = urlbase.replace(':extraId', extraid);
  $.ajax({
    type: "DELETE",
    url: urlbase,
    data: {
      _token: '{{csrf_token()}}'
    },
    dataType: "json",
    success: function (response) {
    }
  });
};
function EditRow(extraFoodId, extraId) {
  var urlbase = '{{ route("operations.restaurant.foods.extra.update", ":extraId") }}';
  urlbase = urlbase.replace(':extraId', extraId);
  $.ajax({
    type: "PUT",
    url: urlbase,
    data: {
      "name": $(`#name${extraFoodId}`).val(),
      "group_extra": $(`#group_extra${extraFoodId}`).val(),
      "price": $(`#price${extraFoodId}`).val(),
       _token: '{{csrf_token()}}'
    },
    dataType: "json",
    success: function (response) {
    }
  });
}

$('.addBtn').click(function()  {
  name = $('.addName').val();
  extraGroup = $('.extra_group').val();
  price = $('.addPrice').val();
  $.ajax({
    type: "POST",
    url: "{{route('operations.restaurant.foods.extra.store')}}",
    data: {
      "name": name,
      "group_extra": extraGroup,
      "price": price,
      "foodId": '{{$food->id}}',
      "restaurant_id": '{{$id}}',
       _token: '{{csrf_token()}}'
    },
    dataType: "json",
    success: function (response) {
      location.reload();
    }
  });
});

    </script>
@endpush
