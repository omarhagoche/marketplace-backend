<div class="card-header">
    <ul class="nav nav-tabs align-items-end card-header-tabs w-100">
      @can('operations.restaurant_foods_index')
      <li class="nav-item">
        <a class="nav-link" href="{!! route('operations.restaurant_foods_index',$id) !!}"><i class="fa fa-list mr-2"></i>{{trans('lang.food_table')}}</a>
      </li>
      @endcan
      @can('operations.restaurant.foods.create')
      <li class="nav-item">
        <a class="nav-link" href="{!! route('operations.restaurant.foods.create',$id) !!}"><i class="fa fa-plus mr-2"></i>{{trans('lang.food_create')}}</a>
      </li>
      @endcan
      @isset($food)
      @can('operations.restaurant.foods.edit')
      <li class="nav-item">
        <a class="nav-link" href="{!! route('operations.restaurant.foods.edit',[$id,$food->id]) !!}"><i class="fa fa-edit mr-2"></i>{{trans('lang.food_edit')}}</a>
      </li>
      @endcan
      @endisset
      
    </ul>
</div>
<br>