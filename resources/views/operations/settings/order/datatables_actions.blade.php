<div class='btn-group btn-group-sm'>
    @can('orders.show')
    <a data-toggle="tooltip" data-placement="bottom" title="{{trans('lang.view_details')}}" href="{{ route('orders.show', $id) }}" class='btn btn-link'>
      <i class="fa fa-eye"></i>
    </a>
    @endcan
  
   

  </div>
  