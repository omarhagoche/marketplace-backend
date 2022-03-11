@push('css_lib')
    @include('operations.layouts.datatables_css')
@endpush


@php
$searchFields = [['name' => 'name', 'data-column' => 1, 'title' => trans('lang.restaurant'), 'type' => 'text', 'value' => request('name')],
 ['name' => 'address', 'data-column' => 2, 'title' => trans('lang.restaurant_address'), 'type' => 'text', 'value' => request('address')],
 ['name' => 'phone', 'data-column' => 3, 'title' => trans('lang.restaurant_phone'), 'type' => 'text', 'value' => request('phone')],
 ['name' => 'moblie', 'data-column' => 4, 'title' => trans('lang.restaurant_mobile'), 'type' => 'text', 'value' => request('moblie')]];
@endphp

{!! Form::open(['route' => ['operations.restaurant_profile.index'],'method' => 'get']) !!}

{{-- Start customer search fields --}}
{{-- <form id="myCustomeSearchForm" novalidate> --}}
<div class="form-row">
    @foreach ($searchFields as $f)
        <div class="col-md-3">
            <label for="validationCustom{{ $f['name'] }}">{{ $f['title'] }}</label>
            @if ($f['type'] == 'select')
                <select name="{{ $f['name'] }}" value="{{ $f['value'] }}" class="form-control searchDTFields"
                    data-column="{{ $f['data-column'] }}" id="validationCustom{{ $f['name'] }}">
                    <option value="">{{ trans('lang.order_order_status_id') }}</option>
                    @foreach ($f['collection'] as $value)
                        <option value="{{ $value->id }}"
                            {{ request('order_status') == $value->id ? 'selected' : '' }}>
                            {{ $value->{$f['property']} }}</option>
                    @endforeach
                </select>
            @else
                <input name="{{ $f['name'] }}" type="{{ $f['type'] }}" value="{{ $f['value'] }}"
                    class="form-control searchDTFields" data-column="{{ $f['data-column'] }}"
                    id="validationCustom{{ $f['name'] }}">
            @endif
        </div>
    @endforeach
    <div class="col-auto align-self-end">
        <button class="btn btn-primary" type="submit"><i class="fa fa-search"></i></button>
    </div>
</div>
{{-- </form> --}}
{!! Form::close() !!}
{{-- End customer search fields --}}

<hr />

{!! $dataTable->table(['width' => '100%']) !!}
<h5 class="text-center">{{ trans('lang.restaurant_no_data_message_notes') }}</h5>
<p class="text-center">{{ trans('lang.restaurant_no_data_message') }}</p>
{{-- إذا لم تظهر أي معلومات في الجدول ، يرجى ملء جميع الحقول ثم حذفها أو استخدام متصفح آخر --}}
@push('scripts_lib')
    @include('layouts.datatables_js')
    {!! $dataTable->scripts() !!}
    {{-- <script>
        $(".searchDTFields").keyup(function() {
            LaravelDataTables["dataTableBuilder"].columns($(this).data('column'))
                .search($(this).val())
                .draw();
        });
    </script> --}}
@endpush
