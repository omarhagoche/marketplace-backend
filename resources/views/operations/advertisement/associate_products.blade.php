@section('content')
<div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-7">
          <h1 class="m-0 text-dark">{{trans('lang.supermarket_plural')}}<small class="ml-3 mr-3">|</small><small>{{trans('lang.supermarket_managment')}}</small></h1>
        </div>
        <div class="col-sm-5">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{url('operations/')}}"><i class="fa fa-dashboard"></i> {{trans('lang.dashboard')}}</a></li>
            <li class="breadcrumb-item"><a href="{!! route('operations.supermarkets.index') !!}">{{trans('lang.supermarket_plural')}}</a>
            </li>
            <li class="breadcrumb-item active">{{trans('lang.supermarket_table')}}</li>
          </ol>
        </div>
      </div>
    </div>
  </div>


@endsection