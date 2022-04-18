@push('css_lib')
    @include('operations.layouts.datatables_css')
@endpush


{{-- id , name , phone , driver type , available , working on --}}
@php
$searchFields = [['name' => 'id', 'data-column' => 1, 'title' => trans('lang.driver_user_id'), 'type' => 'text', 'value' => request('id')],
 ['name' => 'name', 'data-column' => 2, 'title' => trans('lang.user_name'), 'type' => 'text', 'value' => request('name')],
 ['name' => 'phone_number', 'data-column' => 3, 'title' => trans('lang.user_phone_number'), 'type' => 'text', 'value' => request('phone_number')],
 ['name' => 'type', 'data-column' => 6, 'title' => trans('lang.driver_type'), 'type' => 'text', 'value' => request('type')]];
@endphp

{!! Form::open(['route' => ['operations.drivers.index'],
 'method' => 'get']) !!}

{{-- Start customer search fields --}}
{{-- <form id="myCustomeSearchForm" novalidate> --}}
<div class="form-row">
    @foreach ($searchFields as $f)
        <div class="col-md-3">
            <label for="validationCustom{{ $f['name'] }}">{{ $f['title'] }}</label>

            <input name="{{ $f['name'] }}" type="{{ $f['type'] }}" value="{{ $f['value'] }}"
                class="form-control searchDTFields" data-column="{{ $f['data-column'] }}"
                id="validationCustom{{ $f['name'] }}">

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

@push('scripts_lib')
    @include('operations.layouts.datatables_js')
    {!! $dataTable->scripts() !!}
@endpush
