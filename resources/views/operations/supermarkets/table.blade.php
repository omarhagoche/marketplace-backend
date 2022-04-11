@push('css_lib')
    @include('operations.layouts.datatables_css')
@endpush


@php
$searchFields = [['name' => 'name', 'data-column' => 1, 'title' => trans('lang.supermarket_name'), 'type' => 'text', 'value' => request('name')],
 ['name' => 'address', 'data-column' => 2, 'title' => trans('lang.address'), 'type' => 'text', 'value' => request('address')],
 ['name' => 'phone', 'data-column' => 3, 'title' => trans('lang.phone'), 'type' => 'text', 'value' => request('phone')],
 ['name' => 'moblie', 'data-column' => 4, 'title' => trans('lang.mobile'), 'type' => 'text', 'value' => request('moblie')]];
@endphp

{!! Form::open(['route' => ['operations.supermarkets.index'],'method' => 'get']) !!}

<div class="form-row">
    @foreach ($searchFields as $f)
        <div class="col-md-3">
            <label for="validationCustom{{ $f['name'] }}">{{ $f['title'] }}</label>
            <input name="{{ $f['name'] }}" type="{{ $f['type'] }}" value="{{ $f['value'] }}"
                class="form-control searchDTFields" data-column="{{ $f['data-column'] }}"
                id="validationCustom{{ $f['name'] }}">
        </div>
    @endforeach
    <div class="col-auto align-self-end mt-1">
        <button class="btn btn-primary" type="submit"><i class="fa fa-search"></i></button>
    </div>
</div>
{!! Form::close() !!}
<hr />

{!! $dataTable->table(['width' => '100%']) !!}
@push('scripts_lib')
    @include('operations.layouts.datatables_js')
    {!! $dataTable->scripts() !!}
@endpush
