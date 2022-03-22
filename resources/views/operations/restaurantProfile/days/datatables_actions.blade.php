<div class='btn-group btn-group-sm'>
  {{-- @can('operations.restaurant_profile.days.show')
  <a data-toggle="tooltip" data-placement="bottom" title="{{trans('lang.view_details')}}" href="{{ route('days.show', $id) }}" class='btn btn-link'>
    <i class="fa fa-eye"></i>
  </a>
  @endcan --}}

  {{-- @can('operations.restaurant_profile.days.edit') --}}
  <a data-toggle="tooltip" data-placement="bottom" title="{{trans('lang.day_edit')}}"
   href="{{ route('operations.restaurant_profile.days.edit', ['id'=>$restaurant_id,'dayId'=>$id]) }}" class='btn btn-link'>
    <i class="fa fa-edit"></i>
  </a>
  {{-- @endcan --}}

  {{-- @can('operations.restaurant_profile.days.destroy')
{!! Form::open(['route' => ['days.destroy', $id], 'method' => 'delete']) !!}
  {!! Form::button('<i class="fa fa-trash"></i>', [
  'type' => 'submit',
  'class' => 'btn btn-link text-danger',
  'onclick' => "return confirm('Are you sure?')"
  ]) !!}
{!! Form::close() !!}
  @endcan --}}
</div>
