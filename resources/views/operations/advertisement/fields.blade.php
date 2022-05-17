@if($customFields)
<h5 class="col-12 pb-4">{!! trans('lang.main_fields') !!}</h5>
@endif
<div style="flex: 50%;max-width: 50%;padding: 0 4px;" class="column">
<!-- Name Field -->


  
<!-- title Field -->
<div class="form-group row ">
  {!! Form::label('title', trans("lang.title"), ['class' => 'col-3 control-label text-right']) !!}
  <div class="col-9">
      {!! Form::text('title', null,  ['class' => 'form-control','placeholder'=>  trans("lang.title_placeholder")]) !!}
      <div class="form-text text-muted">
          {{ trans("lang.title_help") }}
      </div>
  </div>
</div>
  

<!-- Description Field -->
<div class="form-group row ">
  {!! Form::label('description', trans("lang.category_description"), ['class' => 'col-3 control-label text-right']) !!}
  <div class="col-9">
    {!! Form::textarea('description', null, ['class' => 'form-control','placeholder'=>
     trans("lang.category_description_placeholder")  ]) !!}
    <div class="form-text text-muted">{{ trans("lang.category_description_help") }}</div>
  </div>
</div>
</div>
<div style="flex: 50%;max-width: 50%;padding: 0 4px;" class="column">
  
<!-- link Field -->
<div class="form-group row ">
  {!! Form::label('link', trans("lang.link"), ['class' => 'col-3 control-label text-right']) !!}
  <div class="col-9">
      {!! Form::text('link', null,  ['class' => 'form-control','placeholder'=>  trans("lang.link")]) !!}
      <div class="form-text text-muted">
          {{ trans("lang.link") }}
      </div>
  </div>
</div>
<!-- advertisement_company_id -->
<div class="form-group row ">
  {!! Form::label('advertisement_company_id', trans("lang.adv_Company"),['class' => 'col-3 control-label text-right']) !!}
  <div class="col-9">
    {!! Form::select('advertisement_company_id', $adv_Company, $adv_CompanySelected, ['class' => 'select2 form-control' ]) !!}
    <div class="form-text text-muted">{{ trans("lang.adv_company_help") }}</div>
  </div>
</div>
<!-- Image Field -->
<div class="form-group row">
  {!! Form::label('image', trans("lang.category_image"), ['class' => 'col-3 control-label text-right']) !!}
  <div class="col-9">
    <div style="width: 100%" class="dropzone image" id="image" data-field="image">
      <input type="hidden" name="image">
    </div>
    <a href="#loadMediaModal" data-dropzone="image" data-toggle="modal" data-target="#mediaModal" class="btn btn-outline-{{setting('theme_color','primary')}} btn-sm float-right mt-1">{{ trans('lang.media_select')}}</a>
    <div class="form-text text-muted w-50">
      {{ trans("lang.category_image_help") }}
    </div>
  </div>
</div>
@prepend('scripts')
<script type="text/javascript">
    var var15866134771240834480ble = '';
    @if(isset($advertisement) && $advertisement->hasMedia('image'))
    var15866134771240834480ble = {
        name: "{!! $advertisement->getFirstMedia('image')->name !!}",
        size: "{!! $advertisement->getFirstMedia('image')->size !!}",
        type: "{!! $advertisement->getFirstMedia('image')->mime_type !!}",
        collection_name: "{!! $advertisement->getFirstMedia('image')->collection_name !!}"};
    @endif
    var dz_var15866134771240834480ble = $(".dropzone.image").dropzone({
        url: "{!!url('uploads/store')!!}",
        addRemoveLinks: true,
        maxFiles: 1,
        init: function () {
        @if(isset($advertisement) && $advertisement->hasMedia('image'))
            dzInit(this,var15866134771240834480ble,'{!! url($advertisement->getFirstMediaUrl('image','thumb')) !!}')
        @endif
        },
        accept: function(file, done) {
            dzAccept(file,done,this.element,"{!!config('medialibrary.icons_folder')!!}");
        },
        sending: function (file, xhr, formData) {
            dzSending(this,file,formData,'{!! csrf_token() !!}');
        },
        maxfilesexceeded: function (file) {
            dz_var15866134771240834480ble[0].mockFile = '';
            dzMaxfile(this,file);
        },
        complete: function (file) {
            dzComplete(this, file, var15866134771240834480ble, dz_var15866134771240834480ble[0].mockFile);
            dz_var15866134771240834480ble[0].mockFile = file;
        },
        removedfile: function (file) {
            dzRemoveFile(
                file, var15866134771240834480ble, '{!! url("categories/remove-media") !!}',
                'image', '{!! isset($advertisement) ? $advertisement->id : 0 !!}', '{!! url("uplaods/clear") !!}', '{!! csrf_token() !!}'
            );
        }
    });
    dz_var15866134771240834480ble[0].mockFile = var15866134771240834480ble;
    dropzoneFields['image'] = dz_var15866134771240834480ble;
</script>
@endprepend
</div>
@if($customFields)
<div class="clearfix"></div>
<div class="col-12 custom-field-container">
  <h5 class="col-12 pb-4">{!! trans('lang.custom_field_plural') !!}</h5>
  {!! $customFields !!}
</div>
@endif
<!-- Submit Field -->
<div class="form-group col-12 text-right">
  <button type="submit" class="btn btn-{{setting('theme_color')}}" ><i class="fa fa-save"></i> {{trans('lang.save')}} {{trans('lang.advertisement')}}</button>
  <a href="{!! route('categories.index') !!}" class="btn btn-default"><i class="fa fa-undo"></i> {{trans('lang.cancel')}}</a>
</div>
