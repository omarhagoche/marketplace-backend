@push('css_lib')
@include('operations.layouts.datatables_css')
@endpush

{!! $dataTable->table(['width' => '100%']) !!}

@push('scripts_lib')
@include('operations.layouts.datatables_js')
{!! $dataTable->scripts() !!}
@endpush