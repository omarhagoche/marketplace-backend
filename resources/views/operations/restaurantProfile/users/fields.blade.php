@if($customFields)
    <h5 class="col-12 pb-4">{!! trans('lang.main_fields') !!}</h5>
@endif
<div style="flex: 50%;max-width: 50%;padding: 0 4px;" class="column">
    <!-- Name Field -->
    <div class="form-group row ">
        {!! Form::label('name', trans("lang.user_name"), ['class' => 'col-3 control-label text-right']) !!}
        <div class="col-9">
            {!! Form::text('name',isset($user)? $user->name:'',  ['class' => 'form-control','placeholder'=>  trans("lang.user_name_placeholder")]) !!}
            <div class="form-text text-muted">
                {{ trans("lang.user_name_help") }}
            </div>
        </div>
    </div>

    <!-- Email Field -->
    <div class="form-group row ">
        {!! Form::label('email', trans("lang.user_email"), ['class' => 'col-3 control-label text-right']) !!}
        <div class="col-9">
            {!! Form::text('email', isset($user)?$user->email:'',  ['class' => 'form-control','placeholder'=>  trans("lang.user_email_placeholder"),'disabled' => isset($user)?'disabled':false]) !!}
            {!! isset($user)? Form::hidden('email', $user->email):'' !!}
            <div class="form-text text-muted">
                {{ trans("lang.user_email_help") }}
            </div>
        </div>
    </div>

    <!-- Phone Number Field -->
    <div class="form-group row ">
        {!! Form::label('phone_number', trans("lang.user_phone_number"), ['class' => 'col-3 control-label text-right']) !!}
        <div class="col-9">
            {!! Form::text('phone_number',isset($user)? $user->phone_number:'',  ['class' => 'form-control ','placeholder'=>  trans("lang.user_phone_number_placeholder")]) !!}
            <div class="form-text text-muted">
                {{ trans("lang.user_phone_number_help") }}
            </div>
        </div>
    </div>
    <!-- Password Field -->
    <div class="form-group row ">
        {!! Form::label('password', trans("lang.user_password"), ['class' => 'col-3 control-label text-right']) !!}
        <div class="col-9">
            {!! Form::password('password', ['class' => 'form-control','placeholder'=>  trans("lang.user_password_placeholder")]) !!}
            <div class="form-text text-muted">
                {{ trans("lang.user_password_help") }}
            </div>
        </div>
    </div>

    {{-- @if(!empty($user) && $user->hasAnyRole(['driver','manager']) && !$user->activated_at)
    <!-- 'Activated at Field' -->
    <div class="form-group row">
        {!! Form::label('activated_at', trans("lang.user_activated_at"),['class' => 'col-3 control-label text-right']) !!}
        <div class="checkbox icheck">
            <label class="col-9 ml-2 form-check-inline">
                {!! Form::hidden('activated_at', 0) !!}
                {!! Form::checkbox('activated_at', 1) !!}
            </label>
        </div>
    </div>
    @endif --}}
    
    <!-- 'Active Field' -->
    <div class="form-group row">
        {!! Form::label('active', trans("lang.active"),['class' => 'col-3 control-label text-right']) !!}
        <div class="checkbox icheck">
            <label class="col-9 ml-2 form-check-inline">
                {!! Form::hidden('active', 0) !!}
                {!! Form::checkbox('active', 1) !!}
            </label>
        </div>
    </div>

</div>
<div style="flex: 50%;max-width: 50%;padding: 0 4px;" class="column">
    <!-- $FIELD_NAME_TITLE$ Field -->
    <div class="form-group row">
        {!! Form::label('avatar', trans("lang.user_avatar"), ['class' => 'col-3 control-label text-right']) !!}
        <div class="col-9">
            <div style="width: 100%" class="dropzone avatar" id="avatar" data-field="avatar">
                <input type="hidden" name="avatar">
            </div>
            <a href="#loadMediaModal" data-dropzone="avatar" data-toggle="modal" data-target="#mediaModal" class="btn btn-outline-{{setting('theme_color','primary')}} btn-sm float-right mt-1">{{ trans('lang.media_select')}}</a>
            <div class="form-text text-muted w-50">
                {{ trans("lang.user_avatar_help") }}
            </div>
        </div>
    </div>
    @prepend('scripts')
    <script type="text/javascript">
        var user_avatar = '';
        @if(isset($user) && $user->hasMedia('avatar'))
            user_avatar = {
            name: "{!! $user->getFirstMedia('avatar')->name !!}",
            size: "{!! $user->getFirstMedia('avatar')->size !!}",
            type: "{!! $user->getFirstMedia('avatar')->mime_type !!}",
            collection_name: "{!! $user->getFirstMedia('avatar')->collection_name !!}"
        };
                @endif
        var dz_user_avatar = $(".dropzone.avatar").dropzone({
                url: "{!!url('uploads/store')!!}",
                addRemoveLinks: true,
                maxFiles: 1,
                init: function () {
                    @if(isset($user) && $user->hasMedia('avatar'))
                    dzInit(this, user_avatar, '{!! url($user->getFirstMediaUrl('avatar','thumb')) !!}')
                    @endif
                },
                accept: function (file, done) {
                    dzAccept(file, done, this.element, "{!!config('medialibrary.icons_folder')!!}");
                },
                sending: function (file, xhr, formData) {
                    dzSending(this, file, formData, '{!! csrf_token() !!}');
                },
                maxfilesexceeded: function (file) {
                    dz_user_avatar[0].mockFile = '';
                    dzMaxfile(this, file);
                },
                complete: function (file) {
                    dzComplete(this, file, user_avatar, dz_user_avatar[0].mockFile);
                    dz_user_avatar[0].mockFile = file;
                },
                removedfile: function (file) {
                    dzRemoveFile(
                        file, user_avatar, '{!! url("users/remove-media") !!}',
                        'avatar', '{!! isset($user) ? $user->id : 0 !!}', '{!! url("uplaods/clear") !!}', '{!! csrf_token() !!}'
                    );
                }
            });
        dz_user_avatar[0].mockFile = user_avatar;
        dropzoneFields['avatar'] = dz_user_avatar;
    </script>
    @endprepend
    @can('permissions.index')
<!-- Roles Field -->
    <div class="form-group row ">
        {!! Form::label('roles[]', trans("lang.user_role_id"),['class' => 'col-3 control-label text-right']) !!}
        <div class="col-9">
            {{-- {!! Form::select('roles[]', $role, $user->roles->pluck('name')->toArray(), ['class' => 'select2 form-control' , 'multiple'=>'multiple']) !!} --}}

            {!! Form::select('roles[]', $role, $rolesSelected, ['class' => 'select2 form-control' , 'multiple'=>'multiple']) !!}
            <div class="form-text text-muted">{{ trans("lang.user_role_id_help") }}</div>
        </div>
    </div>
    @endcan

    
    @isset($user->latitude)
        <!-- Google map link -->
        <div class="form-group row justify-content-center">
            <div class="col-auto">
                <a href="https://maps.google.com/?q={{ $user->latitude }},{{ $user->longitude }}" target="_blank" class="btn btn-{{setting('theme_color')}}">Location on google map</a>
            </div>
        </div>
    @endisset

</div>
{{-- @if($customFields)
    <div class="clearfix"></div>
    <div class="col-12 custom-field-container">
        <h5 class="col-12 pb-4">{!! trans('lang.custom_field_plural') !!}</h5>
        {!! $customFields !!}
    </div>
@endif --}}
<!-- Submit Field -->
<div class="form-group col-12 text-right">
    <button type="submit" class="btn btn-{{setting('theme_color')}}"><i class="fa fa-save"></i> {{trans('lang.save')}} {{trans('lang.user')}}</button>
    <a href="{!! route('operations.restaurant_profile.users',$id) !!}" class="btn btn-default"><i class="fa fa-undo"></i> {{trans('lang.cancel')}}</a>
</div>