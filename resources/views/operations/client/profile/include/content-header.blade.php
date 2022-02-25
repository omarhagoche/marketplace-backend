<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">{!! trans('lang.user_profile') !!} <small>/{{$user->name}}</small></h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{url('/dashboard')}}"><i class="fa fa-dashboard"></i> {{trans('lang.dashboard')}}</a></li>
                    
                    <li class="breadcrumb-item"><a href="{{url('/dashboard')}}"> operations</a></li>
                    <li class="breadcrumb-item"><a href="{{route('operations.users.index')}}"> Users</a></li>

                    <li class="breadcrumb-item active">{{trans('lang.user_profile')}}</li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>