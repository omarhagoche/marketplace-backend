@push('css_lib')
    @include('operations.layouts.datatables_css')
@endpush

{{-- Searchable fields
        'name',
        'address',
        'phone',
        'mobile',
        'description',
        'latitude',
        'longitude',
        'information',
        'delivery_fee',
        'default_tax',
        'delivery_range',
        'available_for_delivery',
        'closed',
        'admin_commission',
        'featured',
        'is_restaurant', --}}

@php
$searchFields = [
    ['name' => 'name', 'data-column' => 1, 'title' => trans('lang.restaurant')],
    ['name' => 'address', 'data-column' => 2, 'title' => trans('lang.restaurant_address')],
    ['name' => 'phone', 'data-column' => 3, 'title' => trans('lang.restaurant_phone')],
    ['name' => 'moblie', 'data-column' => 4, 'title' => trans('lang.restaurant_mobile')],
    // ['name' => 'closed', 'data-column' => , 'title' => trans('lang.restauant_closed'), 'type' => 'checkbox'],
    //TODO: search closed, is_restaurant checkobx
];
@endphp

{{-- Start customer search fields --}}
<form id="myCustomeSearchForm" novalidate>
    <div class="form-row">
        @foreach ($searchFields as $f)
            <div class="col-md-3">
                <label for="validationCustom{{ $f['name'] }}">{{ $f['title'] }}</label>
                <input type="{{ $f['type'] ?? 'text' }}" class="form-control searchDTFields"
                    data-column="{{ $f['data-column'] }}" id="validationCustom{{ $f['name'] }}">
            </div>
        @endforeach
    </div>
</form>
{{-- End customer search fields --}}

<hr />

{!! $dataTable->table(['width' => '100%']) !!}
<h5 class="text-center">{{ trans('lang.restaurant_no_data_message_notes') }}</h5>
<p class="text-center">{{ trans('lang.restaurant_no_data_message') }}</p>
{{-- إذا لم تظهر أي معلومات في الجدول ، يرجى ملء جميع الحقول ثم حذفها أو استخدام متصفح آخر --}}
@push('scripts_lib')
    @include('operations.layouts.datatables_js')
    {!! $dataTable->scripts() !!}
    <script>
        $(".searchDTFields").keyup(function() {
            LaravelDataTables["dataTableBuilder"].columns($(this).data('column'))
                .search($(this).val())
                .draw();
        });
    </script>
@endpush
