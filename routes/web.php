<?php

use App\Enums\BlockDirection;
use App\Http\Controllers\BlockPageController;
use App\Http\Controllers\BlockPageMoveController;
use App\Http\Controllers\LayoutBlockController;
use App\Http\Controllers\LayoutController;
use App\Http\Controllers\PreviewController;
use App\Http\Controllers\SiteController;
use App\Http\Controllers\SitePageController;
use App\Http\Controllers\SummarizeTestController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/sites')->name('home');

if (app()->environment(['local', 'testing'])) {
    Route::get('test/summarize', [SummarizeTestController::class, 'index'])->name('test.summarize');
    Route::post('test/summarize', [SummarizeTestController::class, 'store'])->name('test.summarize.store');
}

Route::get('preview/{site:slug}', [PreviewController::class, 'index'])->name('preview.index');
Route::get('preview/{site:slug}/{page:slug}', [PreviewController::class, 'show'])
    ->scopeBindings()
    ->name('preview.show');

Route::resource('sites', SiteController::class);
Route::resource('sites.pages', SitePageController::class)->except(['index'])->scoped(['page' => 'slug']);
Route::resource('sites.layouts', LayoutController::class)->only(['show'])->scoped(['layout' => 'id']);
Route::resource('pages.blocks', BlockPageController::class)->only(['create', 'store', 'edit', 'update', 'destroy'])->scoped(['page' => 'id']);
Route::resource('layouts.blocks', LayoutBlockController::class)->only(['create', 'store', 'edit', 'update', 'destroy'])->scoped(['layout' => 'id']);

Route::patch('blocks/{block}/move/{direction}', BlockPageMoveController::class)
    ->whereIn('direction', BlockDirection::values())
    ->name('blocks.move');
