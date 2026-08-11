<?php

use App\Enums\BlockDirection;
use App\Http\Controllers\BlockPageController;
use App\Http\Controllers\BlockPageMoveController;
use App\Http\Controllers\PreviewController;
use App\Http\Controllers\SiteController;
use App\Http\Controllers\SitePageController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/sites')->name('home');

Route::get('preview/sites/{site:slug}', [PreviewController::class, 'index'])->name('preview.index');
Route::get('preview/{page:id}', [PreviewController::class, 'show'])->name('preview.show');

Route::resource('sites', SiteController::class);
Route::resource('sites.pages', SitePageController::class)->except(['index'])->scoped(['page' => 'slug']);
Route::resource('pages.blocks', BlockPageController::class)->only(['create', 'store', 'destroy'])->scoped(['page' => 'id']);

Route::patch('blocks/{block}/move/{direction}', BlockPageMoveController::class)
    ->whereIn('direction', BlockDirection::values())
    ->name('blocks.move');
