<div class='btn-group btn-group-sm'>
    @can('operations.drivers.show')
        <a data-toggle="tooltip" data-placement="bottom" title="{{ trans('lang.view_details') }}"
            href="{{ route('operations.drivers.show', $id) }}" class='btn btn-link'>
            <i class="fa fa-eye"></i> </a>
    @endcan

    @can('drivers.edit')
        <a data-toggle="tooltip" data-placement="bottom" title="{{ trans('lang.driver_edit') }}"
            href="{{ route('operations.drivers.edit', $id) }}" class='btn btn-link'>
            <i class="fa fa-edit"></i> </a>
    @endcan

    @can('drivers.destroy')
        {!! Form::open(['route' => ['operations.drivers.destroy', $id], 'method' => 'delete']) !!}
        {!! Form::button('<i class="fa fa-trash"></i>', [
    'type' => 'submit',
    'class' => 'btn btn-link text-danger',
    'onclick' => "return confirm('Are you sure?')",
]) !!}
        {!! Form::close() !!}
    @endcan
</div>
