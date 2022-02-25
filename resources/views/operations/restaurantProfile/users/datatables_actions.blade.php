<div class='btn-group btn-group-sm'>
    @can('operations.restaurant_profile.users.edit')
        <a data-toggle="tooltip" data-placement="bottom" title="{{trans('lang.restaurant_edit')}}" 
        href="{{ route('operations.restaurant_profile.users.create',['id'=>$restaurant_id,'userId'=>$id]) }}" class='btn btn-link'>
            <i class="fa fa-edit"></i>
        </a>
    @endcan
    @can('operations.restaurant_profile.users.destroy')
        
    
    {!! Form::open(['route' => ['operations.restaurant_profile.users.destroy', $restaurant_id,$id], 'method' => 'delete']) !!}
        {!! Form::button('<i class="fa fa-trash"></i>', [
        'data-toggle' => 'tooltip',
        'data-placement' => 'bottom',
        'title' => trans('lang.user_delete'),
        'type' => 'submit',
        'class' => 'btn btn-link text-danger',
        'onclick' => "swal({title: ".trans('lang.error').", confirmButtonText: ".trans('lang.ok').",
                                text: data.message,type: 'error', confirmButtonClass: 'btn-danger'});"
        ]) !!}
    {!! Form::close() !!}
    @endcan
</div>
