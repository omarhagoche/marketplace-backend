<div class='btn-group btn-group-sm'>
  @can('operations.advertisement_company.show')
  <a data-toggle="tooltip" data-placement="bottom" title="{{trans('lang.view_details')}}" href="{{ route('operations.advertisement_company.edit', $id) }}" class='btn btn-link'>
    <i class="fa fa-eye"></i>
  </a>
  @endcan

  @can('operations.advertisement_company.edit')
  <a data-toggle="tooltip" data-placement="bottom" title="{{trans('lang.advertisement_company_edit')}}" href="{{ route('operations.advertisement_company.edit', $id) }}" class='btn btn-link'>
    <i class="fa fa-edit"></i>
  </a>
  @endcan

  @can('operations.advertisement_company.destroy')
{!! Form::open(['route' => ['operations.advertisement_company.destroy', $id], 'method' => 'delete']) !!}
  {!! Form::button('<i class="fa fa-trash"></i>', [
  'type' => 'submit',
  'class' => 'btn btn-link text-danger',
  'onclick' => "return confirm('Are you sure?')"
  ]) !!}
{!! Form::close() !!}
  @endcan
</div>
