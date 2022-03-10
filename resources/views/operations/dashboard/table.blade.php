@push('css_lib')
@include('layouts.datatables_css')
@endpush

@php
    $searchFields = [
        ["name" => "restaurant","data-column" => 1, "title" => trans('lang.restaurant'),'type'=>'text','value'=>request('restaurant')],
        ["name" => "client","data-column" => 2, "title" => trans('lang.order_user_id'),'type'=>'text','value'=>request('client')],
        ["name" => "driver","data-column" => 3, "title" => trans('lang.order_driver_id'),'type'=>'text','value'=>request('driver')],
        ["name" => "order_status","data-column" => 4, "title" => trans('lang.order_order_status_id'),'type'=>'select','collection'=>$orderStatuses,'property'=>'status','value'=>request('order_status')],
        ["name" => "start_date","data-column" => 6, "title" =>"Start date",'type'=>'date','value'=>""],
        ["name" => "end_date","data-column" => 6, "title" =>"End date",'type'=>'date','value'=>old('end_date')],

    ];

@endphp

{!! Form::open(['route'=>['operations.dashboard.index'], 'method' => 'get']) !!}
{{-- Start customer search fields --}}
{{-- <form id="myCustomeSearchForm" novalidate> --}}
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
                <input name="{{$f['name']}}" type="{{$f['type']}}" value="{{$f['value']}}" class="form-control searchDTFields" data-column="{{ $f['data-column'] }}" id="validationCustom{{$f['name']}}">
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
   
    //  $(".searchDTFields").change(function(){
    //      console.log('value =>',$(this).val(),'column =>',$(this).data('column'));
    //     LaravelDataTables["dataTableBuilder"].columns($(this).data('column'))
    //     .search($(this).val())
    //     .draw();
    // });
</script>
@endpush