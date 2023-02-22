<?php

namespace VigStudio\VigThemeEditor\Http\Controllers;

use Botble\Base\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use Botble\Theme\Facades\ThemeFacade;

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

        return view('plugins/vig-theme-editor::editor', compact('folders', 'content'));
    }

    public function putFileContent(int $id, Request $request)
    {
        $folders = $this->getFiles();
        $file = $this->arraySearch($id, $folders, 'id');
        file_put_contents($file['path'], $request->input('content'));

        return back();
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
        $path = platform_path() . ThemeFacade::path();

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
