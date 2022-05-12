<div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark">{{trans('lang.supermarket')}}<small class="ml-3 mr-3"> {{ $supermarket->name }} </small></h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{url('/dashboard')}}"><i class="fa fa-dashboard"></i> {{trans('lang.dashboard')}}</a></li>
            <li class="breadcrumb-item"><a href="{!! route('operations.supermarkets.index') !!}">{{trans('lang.supermarket_plural')}}</a>
            </li>
            <li class="breadcrumb-item active">{{trans($path_name)}}</li>
          </ol>
        </div><!-- /.col -->
      </div><!-- /.row -->
    </div><!-- /.container-fluid -->
  </div>