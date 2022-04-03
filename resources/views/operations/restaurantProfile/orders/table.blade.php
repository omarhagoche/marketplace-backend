@push('css_lib')
@include('layouts.datatables_css')
@endpush


{!! $dataTable->table(['width' => '100%'],true) !!}

@push('scripts_lib')
@include('layouts.datatables_js')
{!! $dataTable->scripts() !!}
@endpush