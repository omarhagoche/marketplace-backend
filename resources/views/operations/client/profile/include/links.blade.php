<ul class="nav nav-tabs align-items-end card-header-tabs w-100">
    <li class="nav-item">
        <a class="nav-link {{ Route::is('operations.users.profile.info') ? 'active' : '' }}" href="{!! route('operations.users.profile.info',$user->id) !!}"><i class="fa fa-user mr-2"></i>{{trans('lang.client_info')}}</a>
    </li>
    <li class="nav-item">
        <a class="nav-link  {{ Route::is('operations.users.profile.favorites') ? 'active' : '' }}" href="{!! route('operations.users.profile.favorites',$user->id) !!}"><i class="fa fa-star mr-2"></i>{{trans('lang.client_favorites')}}</a>
    </li>
    <li class="nav-item ">
        <a class="nav-link {{ Route::is('operations.users.profile.orders') ? 'active' : '' }}" href="{!! route('operations.users.profile.orders',$user->id) !!}"><i class="fa fa-first-order mr-2"></i>{{trans('lang.client_orders')}}</a>
    </li>
    <li class="nav-item ">
        <a class="nav-link {{ Route::is('operations.users.profile.coupons') ? 'active' : '' }}" href="{!! url()->current().'/coupons' !!}"><i class="fa fa-credit-card mr-2"></i>{{trans('lang.client_coupons')}}</a>
    </li>
    <li class="nav-item ">
        <a class="nav-link {{ Route::is('operations.users.profile.notes') ? 'active' : '' }}" href="{!! url()->current() .'/notes'!!}"><i class="fa fa-commenting-o  mr-2"></i>{{trans('lang.client_notes')}}</a>
    </li>
    <li class="nav-item ">
        <a class="nav-link {{ Route::is('operations.users.profile.address') ? 'active' : '' }}" href="{!! url()->current().'/address' !!}"><i class="fa fa-map-marker  mr-2"></i>{{trans('lang.client_address')}}</a>
    </li>
    <li class="nav-item ">
        <a class="nav-link {{ Route::is('operations.users.profile.statistics') ? 'active' : '' }}" href="{!! url()->current().'/statistics' !!}"><i class="fa fa-percent  mr-2"></i>{{trans('lang.client_statistics')}}</a>
    </li>
   
    @hasrole('client')
    <div class="ml-auto d-inline-flex">
        <li class="nav-item">
            <a class="nav-link pt-1" href="{!! route('restaurants.create') !!}"><i class="fa fa-check-o"></i> {{trans('lang.app_setting_become_restaurant_owner')}}</a>
        </li>
    </div>
    @endhasrole
</ul>