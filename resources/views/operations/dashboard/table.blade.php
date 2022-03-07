@push('css_lib')
@include('layouts.datatables_css')
@endpush

@php
    $searchFields = [
        ["name" => "restaurant","data-column" => 1, "title" => trans('lang.restaurant'),'type'=>'text'],
        ["name" => "client","data-column" => 2, "title" => trans('lang.order_user_id'),'type'=>'text'],
        ["name" => "driver","data-column" => 3, "title" => trans('lang.order_driver_id'),'type'=>'text'],
        ["name" => "order status","data-column" => 4, "title" => trans('lang.order_order_status_id'),'type'=>'select','collection'=>$orderStatuses,'property'=>'status'],
        // ["name" => "date from ","data-column" => 6, "title" =>"Date of order",'type'=>'date'],

    ];

@endphp

{{-- Start customer search fields --}}
<form id="myCustomeSearchForm" novalidate>
    <div class="form-row">
        @foreach ($searchFields as $f)            
            <div class="col-md-3">
                <label for="validationCustom{{$f['name']}}">{{ $f['title'] }}</label>
               @if ($f['type']=='select')
                    <select  class="form-control searchDTFields" data-column="{{ $f['data-column'] }}" id="validationCustom{{$f['name']}}">
                        <option value="" selected>{{trans('lang.order_order_status_id')}}</option>
                        @foreach ($f['collection'] as $value)
                            <option value="{{ ($value)->{$f['property']} }}">{{($value)->{$f['property']} }}</option>
                        @endforeach
                    </select> 
                @else           
                <input type="{{$f['type']}}" class="form-control searchDTFields" data-column="{{ $f['data-column'] }}" id="validationCustom{{$f['name']}}">
               @endif
            </div>
        @endforeach
    </div>
</form>
{{-- End customer search fields --}}

<hr/>

{!! $dataTable->table(['width' => '100%'],true) !!}

@push('scripts_lib')
@include('layouts.datatables_js')
{!! $dataTable->scripts() !!}

<script> 
   /*  $('#myCustomeSearchForm').submit(function(e){
        e.preventDefault();
        LaravelDataTables["dataTableBuilder"].columns($(this).data('column'))
        .search($(this).val())
        .draw();
    }); */
     $(".searchDTFields").change(function(){
        //  console.log('value =>',$(this).val(),'column =>',$(this).data('column'));
        LaravelDataTables["dataTableBuilder"].columns($(this).data('column'))
        .search($(this).val())
        .draw();
    });
</script>
@endpush