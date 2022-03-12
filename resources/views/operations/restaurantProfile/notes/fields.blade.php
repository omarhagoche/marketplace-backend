
{{-- <div style="flex: 50%;max-width: 50%;padding: 0 4px;" class="column"> --}}
    <!-- Name Field -->
    <div class="form-group row ">
        {!! Form::label('description', trans("lang.restaurant_description"), ['class' => 'col-3 control-label ']) !!}
        <div class="col-12">
            {!! Form::text('text', null,  ['class' => 'form-control','placeholder'=>  trans("lang.user_note_help")]) !!}
            <div class="form-text text-muted">
                {{ trans("lang.user_note_help") }}
            </div>
        </div>
    </div>


{{-- </div> --}}
<!-- Submit Field -->
<div class="form-group col-12 text-right">
    <button type="submit" class="btn btn-{{setting('theme_color')}}"><i class="fa fa-save"></i> {{trans('lang.save')}} {{trans('lang.restaurant')}}</button>
    <a href="{!! route('operations.restaurant_profile.note.index',$restaurant->id) !!}" class="btn btn-default"><i class="fa fa-undo"></i> {{trans('lang.cancel')}}</a>
</div>
