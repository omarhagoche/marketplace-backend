<div class="card-header">
    <ul class="nav nav-tabs align-items-end card-header-tabs w-100">
      @can('operations.supermarkets.index')
      <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('operations.supermarkets.index') ? 'active' : '' }}" href="{!! route('operations.supermarkets.index') !!}"><i class="fa fa-list mr-2"></i>{{trans('lang.supermarket_table')}}</a>
      </li>
      @endcan
      @can('operations.supermarkets.create')
        <li class="nav-item">
          <a class="nav-link {{ request()->routeIs('operations.supermarkets.create') ? 'active' : '' }}" href="{!! route('operations.supermarkets.create') !!}"><i class="fa fa-plus mr-2"></i>{{trans('lang.supermarket_create')}}</a>
        </li>
      @endcan
      @can('operations.supermarkets.edit')
      <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('operations.supermarkets.edit') ? 'active' : '' }}" href="{!!  route('operations.supermarkets.edit',$supermarket->id) !!}"><i class="fa fa-pencil mr-2"></i>{{trans('lang.supermarket_edit')}}</a>
      </li>
      @endcan
      {{-- @can('operations.restaurant_profile.users')
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
        <a class="nav-link {{ request()->routeIs('operations.restaurant.note.*') ? 'active' : '' }}" href="{!! route('operations.restaurant_profile.note.index',$restaurant->id) !!}"><i class="fa fa-sticky-note-o mr-2"></i>{{trans('lang.note_table')}}</a>
      </li> 
      @endcan
      @can('operations.restaurant_profile.days.index')
      <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('operations.restaurant.days.*') ? 'active' : '' }}" href="{!! route('operations.restaurant_profile.days.index',$restaurant->id) !!}"><i class="fa fa-calendar mr-2"></i>{{trans('lang.day_plural')}}</a>
      </li> 
      @endcan --}}
      
    </ul>
</div>