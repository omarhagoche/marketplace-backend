<div class='btn-group btn-group-sm'>
  @can('operations.supermarkets.show')
  <a data-toggle="tooltip" data-placement="bottom" title="{{trans('lang.view_details')}}" href="{{ route('operations.advertisement.edit', $id) }}" class='btn btn-link'>
    <i class="fa fa-eye"></i>
  </a>
  @endcan

  @can('operations.advertisement.edit')
  <a data-toggle="tooltip" data-placement="bottom" title="{{trans('lang.advertisement_edit')}}" href="{{ route('operations.advertisement.edit', $id) }}" class='btn btn-link'>
    <i class="fa fa-edit"></i>
  </a>
  @endcan

  @can('operations.advertisement.destroy')
{!! Form::open(['route' => ['operations.advertisement.destroy', $id], 'method' => 'delete']) !!}
  {!! Form::button('<i class="fa fa-trash"></i>', [
  'type' => 'submit',
  'class' => 'btn btn-link text-danger',
  'onclick' => "return confirm('Are you sure?')"
  ]) !!}
{!! Form::close() !!}
  @endcan
</div>
