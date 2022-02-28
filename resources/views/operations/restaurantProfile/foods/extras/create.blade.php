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

      </tbody>
    </table>
    </div>
@push('scripts')
    <script>
        function formatRows(name,nametext) {
  return '<tr><td class="col-xs-3"><span>'+nametext+'</span><input type="hidden" value="' +name+ '" name="name_extra[]" class="form-control editable" /></td>' +
         '<td class="col-xs-1 text-center"><a href="#" onClick="deleteRow(this)">' +
         '<i class="fa fa-trash-o" aria-hidden="true"></a></td></tr>';
};

function deleteRow(trash) {
  $(trash).closest('tr').remove();
};

function addRow() {
  var name = $('.addName').val();
  var nametext = $('.addName option:selected').text();
  $(formatRows(name,nametext)).insertAfter('#addRow');
  $(input).val('');  
}

$('.addBtn').click(function()  {
  addRow();
});

    </script>
@endpush
