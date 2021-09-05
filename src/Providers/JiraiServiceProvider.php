<?php

namespace Azuriom\Plugin\Jirai\Providers;

use Azuriom\Extensions\Plugin\BasePluginServiceProvider;
use Azuriom\Plugin\Jirai\Models\Permission;
use Illuminate\Pagination\Paginator;

class JiraiServiceProvider extends BasePluginServiceProvider
{
    /**
     * The plugin's global HTTP middleware stack.
     *
     * @var array
     */
    protected $middleware = [
        // \Azuriom\Plugin\Jirai\Middleware\ExampleMiddleware::class,
    ];

    /**
     * The plugin's route middleware groups.
     *
     * @var array
     */
    protected $middlewareGroups = [];

    /**
     * The plugin's route middleware.
     *
     * @var array
     */
    protected $routeMiddleware = [
        // 'example' => \Azuriom\Plugin\Jirai\Middleware\ExampleRouteMiddleware::class,
    ];

    /**
     * The policy mappings for this plugin.
     *
     * @var array
     */
    protected $policies = [
        // User::class => UserPolicy::class,
    ];

    /**
     * Register any plugin services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerMiddlewares();

        //
    }

    /**
     * Bootstrap any plugin services.
     *
     * @return void
     */
    public function boot()
    {
        // $this->registerPolicies();

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
            'jirai.home' => 'jirai::messages.routes-home',
            'jirai.issues.create' => 'jirai::messages.routes-issue-create',
            'jirai.changelogs.create' => 'jirai::messages.routes-changelog-create',
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
                'name' => 'jirai::messages.plugin-name',
                'type' => 'dropdown',
                'icon' => 'fas fa-tags',
                'route' => 'jirai.admin.*',
                'permission' => 'jirai.admin.settings',
                'items' => [
                    'jirai.admin.settings' => 'jirai::messages.global-settings',
                    'jirai.admin.tags.index' => 'jirai::messages.tags-settings'
                ]
            ],
        ];
    }

    /**
     * Return the user navigations routes to register in the user menu.
     *
     * @return array
     */
    protected function userNavigation()
    {
        return [
            //
        ];
    }
}
