    <li class="nav-item">
      <a class="nav-link" href="{!! route('operations.restaurant.extra.index',$id) !!}"><i class="fa fa-list mr-2"></i>{{trans('lang.extra_table')}}</a>
    </li>
    @can('operations.restaurant.create')
    <li class="nav-item">
      <a class="nav-link" href="{!! route('operations.restaurant.create',$id) !!}"><i class="fa fa-plus mr-2"></i>{{trans('lang.extra_create')}}</a>
    </li>
    @endcan
    