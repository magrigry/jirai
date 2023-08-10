<?php

namespace Azuriom\Plugin\Jirai\Providers;

use Azuriom\Extensions\Plugin\BasePluginServiceProvider;
use Azuriom\Plugin\Jirai\Models\Permission;
use Illuminate\Pagination\Paginator;

class JiraiServiceProvider extends BasePluginServiceProvider
{

    /**
     * Register any plugin services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerMiddlewares();
        $this->app->register(EventServiceProvider::class);
    }

    /**
     * Bootstrap any plugin services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadViews();

        $this->loadTranslations();

        $this->loadMigrations();

        $this->registerRouteDescriptions();

        $this->registerAdminNavigation();

        $this->registerUserNavigation();

        Paginator::useBootstrap();

        Permission::registerPermissions();
        Permission::registerInBlade();
    }

    /**
     * Returns the routes that should be able to be added to the navbar.
     *
     * @return array
     */
    protected function routeDescriptions()
    {
        return [
            'jirai.home' => trans('jirai::messages.routes-home'),
            'jirai.issues.create' => trans('jirai::messages.routes-issue-create'),
            'jirai.changelogs.create' => trans('jirai::messages.routes-changelog-create'),
        ];
    }

    /**
     * Return the admin navigations routes to register in the dashboard.
     *
     * @return array
     */
    protected function adminNavigation()
    {

        return [
            'jirai' => [
                'name' => trans('jirai::messages.plugin-name'),
                'type' => 'dropdown',
                'icon' => 'bi bi-tag',
                'route' => 'jirai.admin.*',
                'permission' => 'jirai.admin.settings',
                'items' => [
                    'jirai.admin.settings' => trans('jirai::messages.global-settings'),
                    'jirai.admin.tags.index' => trans('jirai::messages.tags-settings')
                ]
            ],
        ];
    }

}
