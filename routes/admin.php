<?php

use Azuriom\Plugin\Jirai\Controllers\Admin\TagsController;
use Azuriom\Plugin\Jirai\Controllers\Admin\SettingsController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your plugin. These
| routes are loaded by the RouteServiceProvider of your plugin within
| a group which contains the "web" middleware group and your plugin name
| as prefix. Now create something great!
|
*/

Route::middleware('can:jirai.admin.settings')->group(function () {
    Route::resource('tags', TagsController::class);
    Route::get('/settings', [SettingsController::class, 'show'])->name('settings');
    Route::post('/settings', [SettingsController::class, 'save'])->name('settings.save');
});

