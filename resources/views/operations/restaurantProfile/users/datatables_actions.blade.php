<div class='btn-group btn-group-sm'>

    {!! Form::open(['route' => ['users.destroy', $id], 'method' => 'delete']) !!}

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

</div>
