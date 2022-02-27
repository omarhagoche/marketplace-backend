<div class="btn-group" role="group" aria-label="Button group with nested dropdown">
    <div class="btn-group" role="group">
      <button id="btnGroupDrop1" type="button" class="btn btn-light dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        actions
      </button>
      <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
        <a class="dropdown-item" href="{{route('operations.restaurant.foods.edit',[$id,$food->id])}}">{{trans('lang.food_edit')}}</a>
        @can('operations.restaurant.foods.delete')
        {!! Form::open(['route' => ['operations.restaurant.foods.delete', $food->id,$id], 'method' => 'delete']) !!}
          {!! Form::button('<i class="fa fa-trash"></i>', [
          'type' => 'submit',
          'class' => 'btn btn-link text-danger',
          'onclick' => "return confirm('Are you sure?')"
          ]) !!}
        {!! Form::close() !!}
@endcan
      </div>
    </div>
</div>
