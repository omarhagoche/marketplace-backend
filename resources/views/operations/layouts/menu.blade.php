@can('operations.dashboard.index')
    <li class="nav-item">
        <a class="nav-link {{ Request::is('dashboard*') ? 'active' : '' }}" href="{!! route('operations.dashboard.index') !!}">
            @if ($icons)
                <i class="nav-icon fa fa-dashboard"></i>
            @endif
            <p>{{ trans('lang.dashboard') }}</p>
        </a>
    </li>
@endcan



<li class="nav-header">{{ trans('lang.app_management') }}</li>

{{-- <li class="nav-item">
    <a class="nav-link {{ Request::is('categories*') ? 'active' : '' }}" href="{!! route('categories.index') !!}">
        @if ($icons)
            <i class="nav-icon fa fa-folder"></i>
        @endif
        <p>
            {{ trans('lang.category_plural') }}</p>
    </a>
</li>

<li class="nav-item">
    <a class="nav-link {{ Request::is('merchant_categories*') ? 'active' : '' }}" href="{!! route('operations.merchant_categories.index') !!}">
        @if ($icons)
            <i class="nav-icon fa fa-list-ol""></i>
 @endif
                <p>
                    {{ trans('lang.merchant_category_plural') }}</p>
    </a>
</li> --}}

@can('operations.supermarkets.index')

<li class="nav-item {{ request()->routeIs('supermarket*') ||request()->routeIs('operations.supermarket*') ? 'menu-open': '' }}">

    <a class="nav-link {{ Request::is('supermarkets*') ? 'active' : '' }}" href="{!! route('operations.supermarkets.index') !!}">
        @if ($icons)
            <i class="nav-icon fa fa-shopping-cart"></i>
        @endif
        <p>
            {{  Request::is('restaurants*')}}
            {{ trans('lang.supermarket_plural') }}</p>
    </a>
</li>
@endcan
@can('operations.restaurant_profile.index')
    <li
        class="nav-item {{ request()->routeIs('restaurant*') ||request()->routeIs('operations.restaurant*') ||Request::is('requestedRestaurants*') ||Request::is('galleries*') ||Request::is('restaurantReviews*')? 'menu-open': '' }}">
        <a class="nav-link {{ Request::is('restaurants*') ? 'active' : '' }}" href="{!! route('operations.restaurant_profile.index') !!}">
            @if ($icons)
                <i class="nav-icon fa fa-cutlery"></i>
            @endif
            <p>
                {{ trans('lang.restaurant_plural') }}</p>
        </a>
    </li>
@endcan


@can('operations.users.index')
    <li class="nav-item">
        <a class="nav-link {{ Request::is('operations.users*') ? 'active' : '' }}" href="{!! route('operations.users.index') !!}">
            @if ($icons)
                <i class="nav-icon fa  fa-user-circle"></i>
            @endif
            <p>
                {{ trans('lang.client_plural') }}</p>
        </a>
    </li>
@endcan
@can('operations.orders.index')
    <li
        class="nav-item has-treeview {{ Request::is('orders*') || Request::is('orderStatuses*') || Request::is('deliveryAddresses*')? 'menu-open': '' }}">
        <a href="#"
            class="nav-link {{ Request::is('orders*') || Request::is('orderStatuses*') || Request::is('deliveryAddresses*') ? 'active' : '' }}">
            @if ($icons)
                <i class="nav-icon fa fa-usd"></i>
            @endif
            <p>{{ trans('lang.order_plural') }} <i class="right fa fa-angle-left"></i>
            </p>
        </a>
        <ul class="nav nav-treeview">

            @can('operations.orders.index')
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('orders*') ? 'active' : '' }}" href="{!! route('operations.orders.index') !!}">
                        @if ($icons)
                            <i class="nav-icon fa fa-list-alt"></i>
                        @endif
                        <p>
                            {{ trans('lang.order_table') }}</p>
                    </a>
                </li>
            @endcan

            @can('operations.orders.waitting_drivers')
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('orders/waitting-drivers*') ? 'active' : '' }}"
                        href="{!! route('operations.orders.waitting_drivers') !!}">
                        @if ($icons)
                            <i class="nav-icon fa fa-clock-o"></i>
                        @endif
                        <p>
                            {{ trans('lang.order_waitting_drivers_plural') }}</p>
                    </a>
                </li>
            @endcan
        </ul>
    </li>
@endcan

@can('operations.drivers.index')
    <li class="nav-item has-treeview {{ Request::is('driver*') ? 'menu-open' : '' }}">
        <a href="#" class="nav-link {{ Request::is('driver*') ? 'active' : '' }}">
            @if ($icons)
                <i class="nav-icon fa fa-car"></i>
            @endif
            <p>{{ trans('lang.driver_plural') }} <i class="right fa fa-angle-left"></i></p>
        </a>
        <ul class="nav nav-treeview">
            @can('operations.drivers.index')
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('drivers*') ? 'active' : '' }}" href="{!! route('operations.drivers.index') !!}">
                        @if ($icons)
                            <i class="nav-icon fa fa-car"></i>
                        @endif
                        <p>
                            {{ trans('lang.driver_plural') }} </p>
                    </a>
                </li>
            @endcan

            @can('operations.drivers.map')
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('drivers*') ? 'active' : '' }}" href="{!! route('operations.drivers.map') !!}">
                        @if ($icons)
                            <i class="nav-icon fa fa-map"></i>
                        @endif
                        <p>{{ trans('lang.drivers_map') }} </p>
                    </a>
                </li>
            @endcan
        </ul>
    </li>
@endcan


@can('operations.advertisement.index')
    <li class="nav-item has-treeview {{ Request::is('advertisement*') ? 'menu-open' : '' }}">
        <a href="#" class="nav-link {{ Request::is('advertisement*') ? 'active' : '' }}">
            @if ($icons)
                <i class="nav-icon fa fa-rss"></i>
            @endif
            <p>{{ trans('lang.advertisement_plural') }} <i class="right fa fa-angle-left"></i></p>
        </a>
        <ul class="nav nav-treeview">
            @can('operations.advertisement.index')
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('advertisement*') ? 'active' : '' }}" href="{!! route('operations.advertisement.index') !!}">
                        @if ($icons)
                            <i class="nav-icon fa fa-rss"></i>
                        @endif
                        <p>
                            {{ trans('lang.advertisement_plural') }} </p>
                    </a>
                </li>
            @endcan

            @can('operations.advertisement_company.index')
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('advertisement_company*') ? 'active' : '' }}" href="{!! route('operations.advertisement_company.index') !!}">
                        @if ($icons)
                            <i class="nav-icon fa fa-building"></i>
                        @endif
                        <p>{{ trans('lang.advertisement_company') }} </p>
                    </a>
                </li>
            @endcan
        </ul>
    </li>
@endcan