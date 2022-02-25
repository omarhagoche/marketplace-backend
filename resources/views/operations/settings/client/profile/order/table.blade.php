@push('css_lib')
@include('operations.layouts.datatables_css')
@endpush



{{-- Start customer search fields --}}

{{-- End customer search fields --}}

<hr/>

{!! $dataTable->table(['width' => '100%'],true) !!}

@push('scripts_lib')
@include('operations.layouts.datatables_js')
{!! $dataTable->scripts() !!}

<script> 
   /*  $('#myCustomeSearchForm').submit(function(e){
        e.preventDefault();
        LaravelDataTables["dataTableBuilder"].columns($(this).data('column'))
        .search($(this).val())
        .draw();
    }); */
     $(".searchDTFields").keyup(function(){
        LaravelDataTables["dataTableBuilder"].columns($(this).data('column'))
        .search($(this).val())
        .draw();
    });
</script>
@endpush