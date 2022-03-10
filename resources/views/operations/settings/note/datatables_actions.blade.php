<div class='btn-group btn-group-sm'>
  {{-- @can('favorites.destroy') --}}
{!! Form::open(['route' => ['operations.users.profile.destroyNote',$user_id,$id], 'method' => 'delete']) !!}
  {!! Form::button('<i class="fa fa-trash"></i>', [
  'type' => 'submit',
  'class' => 'btn btn-link text-danger',
  'onclick' => "return confirm('Are you sure?')"
  ]) !!}
{!! Form::close() !!}
  {{-- @endcan --}}
</div>
