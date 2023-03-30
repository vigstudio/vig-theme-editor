<?php

namespace VigStudio\VigThemeEditor\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;
use Botble\Base\Traits\LoadAndPublishDataTrait;
use Illuminate\Routing\Events\RouteMatched;

class VigThemeEditorServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    public function register(): void
    {
        $this->setNamespace('plugins/vig-theme-editor')->loadHelpers();
    }

    public function boot(): void
    {
        $this
            ->loadAndPublishConfigurations(['permissions', 'general'])
            ->loadAndPublishTranslations()
            ->loadAndPublishViews()
            ->loadRoutes();

        Event::listen(RouteMatched::class, function () {
            dashboard_menu()
                ->registerItem([
                    'id' => 'cms-plugins-vig-theme-editor',
                    'priority' => 7,
                    'parent_id' => 'cms-core-appearance',
                    'name' => 'plugins/vig-theme-editor::vig-theme-editor.name',
                    'icon' => null,
                    'url' => route('vig-theme-editor.index'),
                    'permissions' => ['vig-theme-editor'],
                ]);
        });
    }
}
