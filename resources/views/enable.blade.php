@extends(BaseHelper::getAdminMasterLayoutTemplate())
@section('content')
    <div class="row">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">{{ __('plugins/vig-theme-editor::vig-theme-editor.enable_title') }}</h5>
                <b class="card-title"> {{ __('plugins/vig-theme-editor::vig-theme-editor.enable_description') }}</b>
            </div>
        </div>
    </div>
@endsection
