<?php

namespace Azuriom\Plugin\Jirai\Providers;

use Azuriom\Extensions\Plugin\BaseRouteServiceProvider;
use Azuriom\Plugin\Jirai\Models\Setting;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends BaseRouteServiceProvider
{
    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function loadRoutes()
    {
        Route::prefix(Setting::getSetting(Setting::SETTING_ROUTE_PREFIX)->getValue())
            ->middleware('web')
            ->name($this->plugin->id.'.')
            ->group(plugin_path($this->plugin->id.'/routes/web.php'));

        Route::prefix('admin/'.$this->plugin->id)
            ->middleware('admin-access')
            ->name($this->plugin->id.'.admin.')
            ->group(plugin_path($this->plugin->id.'/routes/admin.php'));

        Route::prefix('api/'.$this->plugin->id)
            ->middleware('api')
            ->name($this->plugin->id.'.api.')
            ->group(plugin_path($this->plugin->id.'/routes/api.php'));
    }
}
