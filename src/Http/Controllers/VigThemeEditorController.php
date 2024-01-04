<?php

namespace VigStudio\VigThemeEditor\Http\Controllers;

use Botble\Base\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use Theme;
use Illuminate\Filesystem\Filesystem;
use Botble\Base\Http\Responses\BaseHttpResponse;

class VigThemeEditorController extends BaseController
{
    protected $id = 1;

    public function index(Request $request)
    {
        page_title()->setTitle(trans('plugins/vig-theme-editor::vig-theme-editor.name'));

        $folders = $this->getFiles();
        if ($request->input('file')) {
            $content = $this->getContent($request->input('file'), $folders);
        } else {
            $content = '';
        }

        if (config('plugins.vig-theme-editor.general.enable')) {
            return view('plugins/vig-theme-editor::editor', compact('folders', 'content'));
        }

        return view('plugins/vig-theme-editor::enable');
    }

    public function putFileContent(int $id, Request $request, BaseHttpResponse $response)
    {
        if (! config('plugins.vig-theme-editor.general.enable')) {
            return $response->setMessage(trans('plugins/vig-theme-editor::vig-theme-editor.enable_description'));
        }

        $request->validate([
            'content' => 'required|nullable',
        ]);

        $folders = $this->getFiles();
        $file = $this->arraySearch($id, $folders, 'id');

        $fileSystem = new Filesystem();

        if (! $fileSystem->isWritable($file['path'])) {
            return $response->setMessage(trans('plugins/vig-theme-editor::vig-theme-editor.error'));
        }

        file_put_contents($file['path'], $request->input('content'));

        return $response->setMessage(trans('plugins/vig-theme-editor::vig-theme-editor.success'));
    }

    protected function getContent(int $id, array $folders)
    {
        $file = $this->arraySearch($id, $folders, 'id');
        if ($file) {
            return file_get_contents($file['path'], true);
        }

        return '';
    }

    protected function arraySearch(string|int $value, array $array, string|null $key = null): array|bool
    {
        foreach ($array as $k => $v) {
            if ($k === $key && $v === $value) {
                return $array;
            } elseif (is_array($v)) {
                $result = $this->arraySearch($value, $v, $key);
                if ($result !== false) {
                    return $result;
                }
            }
        }

        return false;
    }

    protected function getFiles(): array
    {
        $path = platform_path(Theme::path());

        $folderAllows = [
            'views',
            'partials',
            'layouts',
            'widgets',
        ];

        $result = [];
        foreach ($folderAllows as $folder) {
            $result[$folder] = $this->scanDirectories($path . '/' . $folder, $this->id++);
        }

        return $result;
    }

    protected function scanDirectories($dir): array
    {
        $result = [];
        $files = array_diff(scandir($dir), ['.', '..']);
        foreach ($files as $file) {
            $path = $dir . '/' . $file;
            if (is_dir($path)) {
                $result[] = [
                    'id' => $this->id++,
                    'path' => $path,
                    'name' => $file,
                    'type' => 'directory',
                    'child' => $this->scanDirectories($path, $this->id),
                ];
            } else {
                $result[] = [
                    'id' => $this->id++,
                    'path' => $path,
                    'name' => $file,
                    'type' => 'file',
                    'child' => '',
                ];
            }
        }

        return $result;
    }
}
