@push('css_lib')
@include('operations.layouts.datatables_css')
@endpush


{!! $dataTable->table(['width' => '100%'],true) !!}

@push('scripts_lib')
@include('operations.layouts.datatables_js')
{!! $dataTable->scripts() !!}
@endpush
@push('css_style')
<style>
tfoot {
    display: table-header-group;
}
</style>
@endpush