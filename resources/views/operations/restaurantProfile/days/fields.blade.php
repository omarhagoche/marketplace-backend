
  <!-- OPEN AND CLOSE Field -->
  <div class="form-group  col-6 row ">
    {!! Form::label('open_at', trans("lang.open_at"), ['class' => 'col-3 control-label ']) !!}
    <div class="col-9">
        {!! Form::time('open_at', $day->pivot->open_at??null ,  ['class' => 'form-control','placeholder'=>  trans("lang.open_at")]) !!}
        <div class="form-text text-muted">
            {{ trans("lang.open_at_help") }}
        </div>
    </div>
  </div>
  <div class="form-group col-6 row ">
    {!! Form::label('close_at', trans("lang.close_at"), ['class' => 'col-3 control-label ']) !!}
    <div class="col-9">
        {!! Form::time('close_at', $day->pivot->close_at??null,  ['class' => 'form-control','placeholder'=>  trans("lang.close_at")]) !!}
        <div class="form-text text-muted">
            {{ trans("lang.close_at_help") }}
        </div>
    </div>
  </div>
<!-- Submit Field -->
<div class="form-group col-12 text-right">
  <button type="submit" class="btn btn-{{setting('theme_color')}}" ><i class="fa fa-save"></i> {{trans('lang.save')}} {{trans('lang.day')}}</button>
  <a href="{!! route('operations.restaurant_profile.days.index',$restaurant->id) !!}" class="btn btn-default"><i class="fa fa-undo"></i> {{trans('lang.cancel')}}</a>
</div>
