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
  

<!-- name Field -->
<div class="form-group row ">
  {!! Form::label('name', trans("lang.name"), ['class' => 'col-3 control-label text-right']) !!}
  <div class="col-9">
      {!! Form::text('name', null,  ['class' => 'form-control','placeholder'=>  trans("lang.name_placeholder")]) !!}
      <div class="form-text text-muted">
          {{ trans("lang.name") }}
      </div>
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


 <!-- manager_user_id -->
 <div class="form-group row ">
  {!! Form::label('manager_user_id', trans("lang.manager_user_id"),['class' => 'col-3 control-label text-right']) !!}
  <div class="col-9">
    {!! Form::select('manager_user_id', $manager_user_id, $manager_user_id_Selected, ['class' => 'select2 form-control' ]) !!}
    <div class="form-text text-muted">{{ trans("lang.manager_user_id") }}</div>
  </div>
</div>

 <!-- Image Field -->
 <div class="form-group row">
  {!! Form::label('logo', trans("lang.logo"), ['class' => 'col-3 control-label text-right']) !!}
  <div class="col-9">
      <div style="width: 100%" class="dropzone logo" id="logo" data-field="logo">
          <input type="hidden" name="logo">
      </div>
      <a href="#loadMediaModal" data-dropzone="logo" data-toggle="modal" data-target="#mediaModal" class="btn btn-outline-{{setting('theme_color','primary')}} btn-sm float-right mt-1">{{ trans('lang.media_select')}}</a>
      <div class="form-text text-muted w-50">
          {{ trans("lang.image_help") }}
      </div>
  </div>
</div>



@prepend('scripts')
<script type="text/javascript">
    var var15866134771240834480ble = '';
    @if(isset($advertisement_Company) && $advertisement_Company->hasMedia('logo'))
    var15866134771240834480ble = {
        name: "{!! $advertisement_Company->getFirstMedia('logo')->name !!}",
        size: "{!! $advertisement_Company->getFirstMedia('logo')->size !!}",
        type: "{!! $advertisement_Company->getFirstMedia('logo')->mime_type !!}",
        collection_name: "{!! $advertisement_Company->getFirstMedia('logo')->collection_name !!}"};
    @endif
    var dz_var15866134771240834480ble = $(".dropzone.logo").dropzone({
        url: "{!!url('uploads/store')!!}",
        addRemoveLinks: true,
        maxFiles: 1,
        init: function () {
        @if(isset($advertisement_Company) && $advertisement_Company->hasMedia('logo'))
            dzInit(this,var15866134771240834480ble,'{!! url($advertisement_Company->getFirstMediaUrl('logo','thumb')) !!}')
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
  <button type="submit" class="btn btn-{{setting('theme_color')}}" ><i class="fa fa-save"></i> {{trans('lang.save')}} {{trans('lang.advertisement_company')}}</button>
  <a href="{!! route('operations.advertisement_company.index') !!}" class="btn btn-default"><i class="fa fa-undo"></i> {{trans('lang.cancel')}}</a>
</div>
