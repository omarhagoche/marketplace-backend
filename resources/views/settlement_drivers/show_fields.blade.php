<!-- Id Field -->
<div class="form-group row col-6">
  {!! Form::label('id', 'Id:', ['class' => 'col-3 control-label text-right']) !!}
  <div class="col-9">
    <p>{!! $settlementDriver->id !!}</p>
  </div>
</div>

<!-- Driver Id Field -->
<div class="form-group row col-6">
  {!! Form::label('driver_id', 'Driver Id:', ['class' => 'col-3 control-label text-right']) !!}
  <div class="col-9">
    <p>{!! $settlementDriver->driver->name !!}</p>
  </div>
</div>

<!-- Count Field -->
<div class="form-group row col-6">
  {!! Form::label('count', 'Count:', ['class' => 'col-3 control-label text-right']) !!}
  <div class="col-9">
    <p>{!! $settlementDriver->count !!}</p>
  </div>
</div>

<!-- Amount Field -->
<div class="form-group row col-6">
  {!! Form::label('amount', 'Amount:', ['class' => 'col-3 control-label text-right']) !!}
  <div class="col-9">
    <p>{!! $settlementDriver->amount !!}</p>
  </div>
</div>

<!-- Note Field -->
<div class="form-group row col-6">
  {!! Form::label('note', 'Note:', ['class' => 'col-3 control-label text-right']) !!}
  <div class="col-9">
    <p>{!! $settlementDriver->note !!}</p>
  </div>
</div>

<!-- Creator Id Field -->
<div class="form-group row col-6">
  {!! Form::label('creator_id', 'Creator Id:', ['class' => 'col-3 control-label text-right']) !!}
  <div class="col-9">
    <p>{!! $settlementDriver->creator->name !!}</p>
  </div>
</div>

<!-- Created At Field -->
<div class="form-group row col-6">
  {!! Form::label('created_at', 'Created At:', ['class' => 'col-3 control-label text-right']) !!}
  <div class="col-9">
    <p>{!! $settlementDriver->created_at !!}</p>
  </div>
</div>

<!-- Updated At Field -->
<div class="form-group row col-6">
  {!! Form::label('updated_at', 'Updated At:', ['class' => 'col-3 control-label text-right']) !!}
  <div class="col-9">
    <p>{!! $settlementDriver->updated_at !!}</p>
  </div>
</div>

