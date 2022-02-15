
<div class='btn-group btn-group-sm'>
    @if ($is_default)
    <h5><span class="badge badge-success">Is default</span></h5>
    @else
    <a data-toggle="tooltip" data-placement="bottom" title="{{trans('lang.set_default')}}" href="{{ route('operations.users.profile.address.setDefault', ['userId'=>$user['id'],'addressId'=>$id]) }}" class='btn btn-outline-info'>
        Set default </a>
    @endif
    {!! Form::open([
        'route' => ['operations.users.profile.address.delete', 'userId'=>$user['id'],'addressId'=>$id], 'method' => 'delete']) !!}
    {!! Form::button('<i class="fa fa-trash"></i>', [
        'type' => 'submit',
        'class' => 'btn btn-link text-danger',
        'onclick' => "return confirm('Are you sure?')"
        ]) !!}

{!! Form::close() !!}
</div>

