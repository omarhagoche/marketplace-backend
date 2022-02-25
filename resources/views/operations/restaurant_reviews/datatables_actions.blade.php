<div class='btn-group btn-group-sm'>
    @if(in_array($id,$myReviews))
        @can('operations.restaurantReviews.show')
            <a data-toggle="tooltip" data-placement="bottom" title="{{trans('lang.view_details')}}" href="{{ route('operations.restaurantReviews.show', $id) }}" class='btn btn-link'>
                <i class="fa fa-eye"></i> </a>
        @endcan

        @can('operations.restaurantReviews.edit')
            <a data-toggle="tooltip" data-placement="bottom" title="{{trans('lang.restaurant_review_edit')}}" href="{{ route('operations.restaurantReviews.edit', $id) }}" class='btn btn-link'>
                <i class="fa fa-edit"></i> </a>
        @endcan

        @can('operations.restaurantReviews.destroy')
            {!! Form::open(['route' => ['operations.restaurantReviews.destroy', $id], 'method' => 'delete']) !!}
            {!! Form::button('<i class="fa fa-trash"></i>', [
            'type' => 'submit',
            'class' => 'btn btn-link text-danger',
            'onclick' => "return confirm('Are you sure?')"
            ]) !!}
            {!! Form::close() !!}
        @endcan
    @endif
</div>
