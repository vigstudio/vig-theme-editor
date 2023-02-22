@foreach ($dir as $file)
    @if ($file['type'] === 'directory')
        <b class="card-title">{{ vig_iteration_loop($loop) }} <i class="fa-sharp fa-regular fa-folder"></i> {{ $file['name'] }}</b><br />
    @else
        <a href="{{ route('vig-theme-editor.index', ['file' => $file['id']]) }}">{{ vig_iteration_loop($loop) }} {{ $file['name'] }}</a><br />
    @endif
    @includeWhen(is_array($file['child']), 'plugins/vig-theme-editor::editor.loop-dir', ['dir' => $file['child']])
@endforeach
