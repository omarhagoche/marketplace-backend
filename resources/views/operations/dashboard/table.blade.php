@push('css_lib')
@include('layouts.datatables_css')
@endpush

@php
    $searchFields = [
        ["name" => "restaurant","data-column" => 1, "title" => trans('lang.restaurant'),'type'=>'text','value'=>''],
        ["name" => "client","data-column" => 2, "title" => trans('lang.order_user_id'),'type'=>'text','value'=>''],
        ["name" => "driver","data-column" => 3, "title" => trans('lang.order_driver_id'),'type'=>'text','value'=>''],
        ["name" => "order status","data-column" => 4, "title" => trans('lang.order_order_status_id'),'type'=>'select','collection'=>$orderStatuses,'property'=>'status','value'=>''],
        ["name" => "datefrom ","data-column" => 6, "title" =>"Date of order",'type'=>'date','value'=>date("Y/m/d")],

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
                <input type="{{$f['type']}}" value="{{$f['value']}}" class="form-control searchDTFields" data-column="{{ $f['data-column'] }}" id="validationCustom{{$f['name']}}">
               @endif
            </div>
        @endforeach
          {{-- <div class="col-auto align-self-end">
            <button class="btn btn-primary" type="submit"><i class="fa fa-search"></i></button>
        </div> --}}
    </div>
</form>
{{-- End customer search fields --}}

<hr/>

{!! $dataTable->table(['width' => '100%'],true) !!}

@push('scripts_lib')
@include('layouts.datatables_js')
{!! $dataTable->scripts() !!}

<script> 

    // $('#myCustomeSearchForm').submit(function(e){
    //     e.preventDefault();
    //     console.log('ewgewtgrqeg');
    //     LaravelDataTables["dataTableBuilder"].columns($(this).data('column'))
    //     .search($(this).val())
    //     .draw();
    // }); 
   
     $(".searchDTFields").change(function(){
         console.log('value =>',$(this).val(),'column =>',$(this).data('column'));
        LaravelDataTables["dataTableBuilder"].columns($(this).data('column'))
        .search($(this).val())
        .draw();
    });
</script>
@endpush