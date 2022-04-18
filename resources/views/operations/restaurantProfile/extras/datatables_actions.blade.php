<div class='btn-group btn-group-sm'>
  @can('operations.extras.show')
  <a data-toggle="tooltip" data-placement="bottom" title="{{trans('lang.view_details')}}" href="{{ route('operations.extras.show', $id) }}" class='btn btn-link'>
    <i class="fa fa-eye"></i>
  </a>
  @endcan

  @can('operations.restaurant.extra.edit')
  <a data-toggle="tooltip" data-placement="bottom" title="{{trans('lang.extra_edit')}}" href="{{ route('operations.restaurant.extra.edit',[$extra->id, $id]) }}" class='btn btn-link'>
    <i class="fa fa-edit"></i>
  </a>
  @endcan

  @can('operations.extras.destroy')
{!! Form::open(['route' => ['operations.extras.destroy', $extra->id], 'method' => 'delete']) !!}
  {!! Form::button('<i class="fa fa-trash"></i>', [
  'type' => 'submit',
  'class' => 'btn btn-link text-danger',
  'onclick' => "return confirm('Are you sure?')"
  ]) !!}
{!! Form::close() !!}
  @endcan
</div>
