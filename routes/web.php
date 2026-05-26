<?php

use App\Http\Controllers\BlockPageController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\SiteController;
use App\Http\Controllers\SitePageController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/sites')->name('home');

Route::get('/chat', [ChatController::class, 'index'])->name('chat.index');
Route::post('/chat', [ChatController::class, 'store'])->name('chat.store');
Route::post('/chat/clear', [ChatController::class, 'clear'])->name('chat.clear');

Route::get('/preview/{site:slug}', [SiteController::class, 'previewHome'])
    ->name('sites.preview.home');

Route::get('/preview/{site:slug}/{page}', [SiteController::class, 'preview'])
    ->name('sites.preview');

Route::resource('sites', SiteController::class);
Route::resource('sites.pages', SitePageController::class)
    ->except(['index', 'show'])
    ->scoped(['page' => 'slug']);

Route::scopeBindings()->group(function () {
    Route::get('/sites/{site}/pages/{page:slug}/blocks/create', [BlockPageController::class, 'create'])
        ->name('sites.pages.blocks.create');
    Route::post('/sites/{site}/pages/{page:slug}/blocks', [BlockPageController::class, 'store'])
        ->name('sites.pages.blocks.store');
    Route::delete('/sites/{site}/pages/{page:slug}/blocks/{block_page}', [BlockPageController::class, 'destroy'])
        ->name('sites.pages.blocks.destroy');
});
