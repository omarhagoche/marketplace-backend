<div class="col-md-12">
    <table class="table">
      <thead>
        <tr>
          <th>name</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <tr id="addRow">
          <td class="col-xs-3">
            {!! Form::select('name_extras', $extra, null, ['class' => 'select2 form-control addName']) !!}
          </td>
          <td class="col-xs-1 text-center">
            <span class="addBtn">
                <i class="fa fa-plus"></i>
              </span>
          </td>
        </tr>
        @foreach ($food->extrasFood as $extraFood) 
        <tr>
            <td class="col-xs-3">{!! Form::select('name_extra[]', $extra, $extraFood->extra_id, ['id'=>'name'.$extraFood->id,'class' => 'select2 form-control editable']) !!}</td>
            <td class="col-xs-1 text-center"><a  href="javascript:void(0)" onClick="EditRow({{$extraFood->id }},{{$extraFood->extra->id}})"> 
            <i class="fa fa-edit" aria-hidden="true"></a></td>
            <td class="col-xs-1 text-center"><a href="javascript:void(0)" onClick="deleteRow(this,{{$extraFood->id}})"> 
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


function deleteRow(trash,extraFoodId) {
  console.log(extraFoodId)
  $(trash).closest('tr').remove();
  var urlbase = '{{ route("operations.restaurant.foods.extra.delete", ":extraFoodId") }}';
  urlbase = urlbase.replace(':extraFoodId', extraFoodId);
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
function EditRow(extraFoodId) {
  var urlbase = '{{ route("operations.restaurant.foods.extra.update", ":extraFoodId") }}';
  urlbase = urlbase.replace(':extraFoodId', extraFoodId);
  $.ajax({
    type: "PUT",
    url: urlbase,
    data: {
      "extraId": $(`#name${extraFoodId}`).val(),
       _token: '{{csrf_token()}}'
    },
    dataType: "json",
    success: function (response) {
    }
  });
}

$('.addBtn').click(function()  {
  name = $('.addName').val();
  $.ajax({
    type: "POST",
    url: "{{route('operations.restaurant.foods.extra.store')}}",
    data: {
      "name_extras": name,
      "foodId": '{{$food->id}}',
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
