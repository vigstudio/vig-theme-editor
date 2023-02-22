@extends(BaseHelper::getAdminMasterLayoutTemplate())
@section('content')
    <div class="row">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">
                        File of Theme: {{ Theme::getThemeName() }}
                    </h5>
                    @foreach ($folders as $key => $files)
                        <h6 class="card-title"> {{ vig_iteration_loop($loop) }} <i class="fa-sharp fa-regular fa-folder"></i> {{ $key }}</h6>
                        @foreach ($files as $file)
                            @if ($file['type'] === 'directory')
                                <b class="card-title"> {{ vig_iteration_loop($loop) }} <i class="fa-sharp fa-regular fa-folder"></i> {{ $file['name'] }}</b><br />
                            @else
                                <a href="{{ route('vig-theme-editor.index', ['file' => $file['id']]) }}">{{ vig_iteration_loop($loop) }} {{ $file['name'] }}</a><br />
                            @endif
                            @includeWhen(is_array($file['child']), 'plugins/vig-theme-editor::editor.loop-dir', ['dir' => $file['child']])
                        @endforeach
                    @endforeach
                </div>
            </div>
        </div>

        <div class="col-md-9">
            @if (request()->input('file'))
                <form action="{{ route('vig-theme-editor.put', request()->input('file')) }}" method="POST">
                    @csrf
                    <div class="card">
                        <div class="card-body">
                            <textarea id="code" name="content">{{ $content }}</textarea>

                        </div>
                        <div class="card-footer">

                            <button class="btn btn-success ">{{ __('plugins/vig-theme-editor::vig-theme-editor.submit') }}</button>

                        </div>
                    </div>
                </form>
            @endif
        </div>
    </div>
@endsection

@section('head')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/6.65.7/codemirror.min.css" integrity="sha512-uf06llspW44/LZpHzHT6qBOIVODjWtv4MxCricRxkzvopAlSWnTf6hpZTFxuuZcuNE9CBQhqE0Seu1CoRk84nQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/6.65.7/theme/material-darker.min.css" integrity="sha512-2OhXH4Il3n2tHKwLLSDPhrkgnLBC+6lHGGQzSFi3chgVB6DJ/v6+nbx+XYO9CugQyHVF/8D/0k3Hx1eaUK2K9g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
@endsection

@section('javascript')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/6.65.7/codemirror.min.js" integrity="sha512-8RnEqURPUc5aqFEN04aQEiPlSAdE0jlFS/9iGgUyNtwFnSKCXhmB6ZTNl7LnDtDWKabJIASzXrzD0K+LYexU9g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/6.65.7/addon/selection/active-line.min.js" integrity="sha512-0sDhEPgX5DsfNcL5ty4kP6tR8H2vPkn40GwA0RYTshkbksURAlsRVnG4ECPPBQh7ZYU6S3rGvp5uhlGQUNrcmA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/6.65.7/addon/edit/matchbrackets.min.js" integrity="sha512-GSYCbN/le5gNmfAWVEjg1tKnOH7ilK6xCLgA7c48IReoIR2g2vldxTM6kZlN6o3VtWIe6fHu/qhwxIt11J8EBA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/6.65.7/mode/xml/xml.min.js" integrity="sha512-LarNmzVokUmcA7aUDtqZ6oTS+YXmUKzpGdm8DxC46A6AHu+PQiYCUlwEGWidjVYMo/QXZMFMIadZtrkfApYp/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/6.65.7/mode/javascript/javascript.min.js" integrity="sha512-I6CdJdruzGtvDyvdO4YsiAq+pkWf2efgd1ZUSK2FnM/u2VuRASPC7GowWQrWyjxCZn6CT89s3ddGI+be0Ak9Fg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/6.65.7/mode/css/css.min.js" integrity="sha512-rQImvJlBa8MV1Tl1SXR5zD2bWfmgCEIzTieFegGg89AAt7j/NBEe50M5CqYQJnRwtkjKMmuYgHBqtD1Ubbk5ww==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/6.65.7/mode/htmlmixed/htmlmixed.min.js" integrity="sha512-HN6cn6mIWeFJFwRN9yetDAMSh+AK9myHF1X9GlSlKmThaat65342Yw8wL7ITuaJnPioG0SYG09gy0qd5+s777w==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/6.65.7/mode/php/php.min.js" integrity="sha512-jZGz5n9AVTuQGhKTL0QzOm6bxxIQjaSbins+vD3OIdI7mtnmYE6h/L+UBGIp/SssLggbkxRzp9XkQNA4AyjFBw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        window.onload = function() {
            editor = CodeMirror.fromTextArea(document.getElementById("code"), {
                lineNumbers: true,
                styleActiveLine: true,
                matchBrackets: true,
                theme: 'material-darker',
            }).setSize("100%", 700);
        };
    </script>
@endsection
