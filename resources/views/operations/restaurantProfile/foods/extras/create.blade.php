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

      </tbody>
    </table>
    </div>
@push('scripts')
    <script>
        function formatRows(name, prefer, price) {
  return '<tr><td class="col-xs-3"><input type="text" value="' +name+ '" name="name_extra[]" class="form-control editable" /></td>' +
         '<td class="col-xs-3"><input type="text" value="' +prefer+ '" name="group_extra[]" class="form-control editable" /></td>' +
         '<td class="col-xs-3"><input type="number" value="' +price+ '" name="price_extra[]" class="form-control editable" /></td>' +
         '<td class="col-xs-1 text-center"><a href="#" onClick="deleteRow(this)">' +
         '<i class="fa fa-trash-o" aria-hidden="true"></a></td></tr>';
};

function deleteRow(trash) {
  $(trash).closest('tr').remove();
};

function addRow() {
  var name = $('.addName').val();
  var extraGroup = $('.extra_group').val();
  var price = $('.addPrice').val();
  $(formatRows(name,extraGroup,price)).insertAfter('#addRow');
  $(input).val('');  
}

$('.addBtn').click(function()  {
  addRow();
});

    </script>
@endpush
