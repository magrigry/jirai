<?php

use Azuriom\Plugin\Jirai\Controllers\AttachmentController;
use Azuriom\Plugin\Jirai\Controllers\ChangelogController;
use Azuriom\Plugin\Jirai\Controllers\IssueController;
use Azuriom\Plugin\Jirai\Controllers\JiraiHomeController;
use Azuriom\Plugin\Jirai\Controllers\MessageController;
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

Route::get('/', [JiraiHomeController::class, 'index'])->name('home');

Route::resource('issues', IssueController::class);
Route::resource('messages', MessageController::class)->except('show');
Route::resource('changelogs', ChangelogController::class);
Route::post('/attachments', [AttachmentController::class, 'storeAttachment'])
    ->name('attachments')
    ->middleware('can:jirai.post.attachments')
    ->middleware('throttle:10,1')
    ->middleware('throttle:100,3600');
