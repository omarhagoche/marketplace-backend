<div class="card-header">
    <ul class="nav nav-tabs align-items-end card-header-tabs w-100">
      @can('restaurants.index')
      <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('operations.restaurant_profile.index') ? 'active' : '' }}" href="{!! route('operations.restaurant_profile.index') !!}"><i class="fa fa-list mr-2"></i>{{trans('lang.restaurant_table')}}</a>
      </li>
      @endcan
      @can('operations.restaurant.create')
      <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('operations.restaurant_profile.create') ? 'active' : '' }}" href="{!! route('operations.restaurant_profile.create') !!}"><i class="fa fa-plus mr-2"></i>{{trans('lang.restaurant_create')}}</a>
      </li>
      @endcan
      @can('operations.restaurant_profile_edit')
      <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('operations.restaurant_profile_edit') ? 'active' : '' }}" href="{!!  route('operations.restaurant_profile_edit',$restaurant->id) !!}"><i class="fa fa-pencil mr-2"></i>{{trans('lang.restaurant_edit')}}</a>
      </li>
      @endcan
      @can('operations.restaurant_profile.users')
      <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('operations.restaurant_profile.users') ? 'active' : '' }}" href="{!! route('operations.restaurant_profile.users',$id) !!}"><i class="fa fa-pencil mr-2"></i>{{trans('lang.user')}}</a>
      </li>
      @endcan
      @can('operations.restaurant_review')
      <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('operations.restaurant_review*') ? 'active' : '' }}" href="{!! route('operations.restaurant_review',$restaurant->id) !!}"><i class="fa fa-eye mr-2"></i>{{trans('lang.restaurant_review')}}</a>
      </li> 
      @endcan
      @can('operations.restaurant.foods.index')
      <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('operations.restaurant.foods.*') ? 'active' : '' }}" href="{!! route('operations.restaurant.foods.index',$restaurant->id) !!}"><i class="fa fa-eye mr-2"></i>{{trans('lang.food_table')}}</a>
      </li> 
      @endcan
      @can('operations.restaurant.extra.index')
      <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('operations.restaurant.extra.*') ? 'active' : '' }}" href="{!! route('operations.restaurant.extra.index',$restaurant->id) !!}"><i class="fa fa-eye mr-2"></i>{{trans('lang.extra_table')}}</a>
      </li> 
      @endcan
      
    </ul>
</div>