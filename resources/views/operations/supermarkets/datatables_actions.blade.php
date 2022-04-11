<div class='btn-group btn-group-sm'>
  @can('operations.supermarkets.show')
  <a data-toggle="tooltip" data-placement="bottom" title="{{trans('lang.view_details')}}" href="{{ route('operations.supermarkets.edit', $id) }}" class='btn btn-link'>
    <i class="fa fa-eye"></i>
  </a>
  @endcan

  @can('operations.supermarkets.edit')
  <a data-toggle="tooltip" data-placement="bottom" title="{{trans('lang.supermarket_edit')}}" href="{{ route('operations.supermarkets.edit', $id) }}" class='btn btn-link'>
    <i class="fa fa-edit"></i>
  </a>
  @endcan

  @can('operations.supermarkets.destroy')
{!! Form::open(['route' => ['operations.supermarkets.destroy', $id], 'method' => 'delete']) !!}
  {!! Form::button('<i class="fa fa-trash"></i>', [
  'type' => 'submit',
  'class' => 'btn btn-link text-danger',
  'onclick' => "return confirm('Are you sure?')"
  ]) !!}
{!! Form::close() !!}
  @endcan
</div>
