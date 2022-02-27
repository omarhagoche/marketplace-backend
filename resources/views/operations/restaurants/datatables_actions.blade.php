<div class='btn-group btn-group-sm'>
  @can('operations.restaurant_profile.show')
  <a data-toggle="tooltip" data-placement="bottom" title="{{trans('lang.view_details')}}" href="{{ route('operations.restaurant_profile.show', $id) }}" class='btn btn-link'>
    <i class="fa fa-eye"></i>
  </a>
  @endcan

  @can('operations.restaurant_profile_edit')
  <a data-toggle="tooltip" data-placement="bottom" title="{{trans('lang.restaurant_edit')}}" href="{{ route('operations.restaurant_profile_edit', $id) }}" class='btn btn-link'>
    <i class="fa fa-edit"></i>
  </a>
  @endcan

  @can('restaurants.destroy')
{!! Form::open(['route' => ['operations.restaurant_profile.destroy', $id], 'method' => 'delete']) !!}
  {!! Form::button('<i class="fa fa-trash"></i>', [
  'type' => 'submit',
  'class' => 'btn btn-link text-danger',
  'onclick' => "return confirm('Are you sure?')"
  ]) !!}
{!! Form::close() !!}
  @endcan
</div>
