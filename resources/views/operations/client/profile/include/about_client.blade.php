<div class="col-md-3">

    <!-- Profile Image -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title"><i class="fa fa-user mr-2"></i> {{trans('lang.user_about_client')}}</h3>
        </div>
        <div class="card-body box-profile">
            <div class="text-center">
                <img src="{{$user->getFirstMediaUrl('avatar','icon')}}" class="profile-user-img img-fluid img-circle" alt="{{$user->name}}">
            </div>
            <h3 class="profile-username text-center">{{$user->name}}</h3>
            <p class="text-muted text-center">{{implode(', ',$rolesSelected)}}</p>
            <a class="btn btn-outline-{{setting('theme_color')}} btn-block" href="mailto:{{$user->email}}"><i class="fa fa-envelope mr-2"></i>{{$user->email}}
            </a>
        </div>
        <!-- /.card-body -->
    </div>
    <!-- /.card -->

    @if($customFields)
    <!-- About Me Box -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><i class="fa fa-list mr-2"></i>{{trans('lang.custom_field_plural')}}</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                @foreach($customFieldsValues as $value)
                    <strong>{{trans('lang.user_'.$value->customField->name)}}</strong>
                    <p class="text-muted">
                        {!! $value->view !!}
                    </p>
                    @if(!$loop->last)
                        <hr> @endif
                @endforeach
            </div>
            <!-- /.card-body -->
        </div>
    <!-- /.card -->
    @endif
</div>