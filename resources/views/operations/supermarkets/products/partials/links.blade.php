<div class="card-header">
    <ul class="nav nav-tabs align-items-end card-header-tabs w-100">
      {{-- @can('operations.supermarkets.products.index') --}}
      <li class="nav-item">
        <a class="nav-link {{ request()->routeIs() ? 'active' : '' }}" href="{!! route('operations.supermarkets.products.index',$id) !!}"><i class="fa fa-list mr-2"></i>{{trans('lang.product_table')}}</a>
      </li>
      {{-- @endcan
      @can('operations.supermarkets.products.create') --}}
      <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('operations.supermarkets.products.create') ? 'active' : '' }}" href="{!! route('operations.supermarkets.products.create',$id) !!}"><i class="fa fa-plus mr-2"></i>{{trans('lang.product_create')}}</a>
      </li>
      {{-- @endcan --}}
      @isset($product)
      {{-- @can('operations.supermarkets.products.edit') --}}
      <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('operations.supermarkets.products.edit') ? 'active' : '' }}" href="{!! route('operations.supermarkets.products.edit',[$id,$product->id]) !!}"><i class="fa fa-edit mr-2"></i>{{trans('lang.product_edit')}}</a>
      </li>
      {{-- @endcan --}}
      @endisset
      
    </ul>
</div>
<br>