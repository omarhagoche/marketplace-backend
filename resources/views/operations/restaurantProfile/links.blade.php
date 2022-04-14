<div class="card-header">
    <ul class="nav nav-tabs align-items-end card-header-tabs w-100">
      @can('operations.restaurants.index')
      <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('operations.restaurants.index') ? 'active' : '' }}" href="{!! route('operations.restaurants.index') !!}"><i class="fa fa-list mr-2"></i>{{trans('lang.restaurant_table')}}</a>
      </li>
      @endcan
      @can('operations.restaurants.create')
        <li class="nav-item">
          <a class="nav-link {{ request()->routeIs('operations.restaurants.create') ? 'active' : '' }}" href="{!! route('operations.restaurants.create') !!}"><i class="fa fa-plus mr-2"></i>{{trans('lang.restaurant_create')}}</a>
        </li>
      @endcan
      @can('operations.restaurant_profile_edit')
      <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('operations.restaurant_profile_edit') ? 'active' : '' }}" href="{!!  route('operations.restaurant_profile_edit',$restaurant->id) !!}"><i class="fa fa-pencil mr-2"></i>{{trans('lang.restaurant_edit')}}</a>
      </li>
      @endcan
      @can('operations.restaurant_profile.users')
      <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('operations.restaurant_profile.users') ? 'active' : '' }}" href="{!! route('operations.restaurant_profile.users',$restaurant->id) !!}"><i class="fa fa-users mr-2"></i>{{trans('lang.user')}}</a>
      </li>
      @endcan
      @can('operations.restaurant_review')
      <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('operations.restaurant_review*') ? 'active' : '' }}" href="{!! route('operations.restaurant_review',$restaurant->id) !!}"><i class="fa fa-eye mr-2"></i>{{trans('lang.restaurant_review')}}</a>
      </li> 
      @endcan
      @can('operations.restaurant.foods.index')
      <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('operations.restaurant.foods.*') ? 'active' : '' }}" href="{!! route('operations.restaurant.foods.index',$restaurant->id) !!}"><i class="fa fa-shopping-basket mr-2"></i>{{trans('lang.food_table')}}</a>
      </li> 
      @endcan
      @can('operations.restaurant.extra.index')
      <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('operations.restaurant.extra.*') ? 'active' : '' }}" href="{!! route('operations.restaurant.extra.index',$restaurant->id) !!}"><i class="fa fa-th-list mr-2"></i>{{trans('lang.extra_table')}}</a>
      </li> 
      @endcan
      @can('operations.restaurant_profile.note.index')
      <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('operations.restaurant_profile.note.*') ? 'active' : '' }}" href="{!! route('operations.restaurant_profile.note.index',$restaurant->id) !!}"><i class="fa fa-eye mr-2"></i>{{trans('lang.note_table')}}</a>
      </li> 
      @endcan
      @can('operations.restaurant_profile.order.index')
      <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('operations.restaurant_profile.order.*') ? 'active' : '' }}" href="{!! route('operations.restaurant_profile.order.index',$restaurant->id) !!}"><i class="fa fa-eye mr-2"></i>{{trans('lang.order_table')}}</a>
        <a class="nav-link {{ request()->routeIs('operations.restaurant.note.*') ? 'active' : '' }}" href="{!! route('operations.restaurant_profile.note.index',$restaurant->id) !!}"><i class="fa fa-sticky-note-o mr-2"></i>{{trans('lang.note_table')}}</a>
      </li> 
      @endcan
      @can('operations.restaurant_profile.days.index')
      <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('operations.restaurant.days.*') ? 'active' : '' }}" href="{!! route('operations.restaurant_profile.days.index',$restaurant->id) !!}"><i class="fa fa-calendar mr-2"></i>{{trans('lang.day_plural')}}</a>
      </li> 
      @endcan
      
    </ul>
</div>