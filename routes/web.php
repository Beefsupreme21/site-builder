<?php

use App\Enums\BlockDirection;
use App\Http\Controllers\BlockPageController;
use App\Http\Controllers\BlockPageMoveController;
use App\Http\Controllers\BlockVariantAiController;
use App\Http\Controllers\LayoutBlockController;
use App\Http\Controllers\LayoutController;
use App\Http\Controllers\PreviewController;
use App\Http\Controllers\SiteController;
use App\Http\Controllers\SitePageController;
use Illuminate\Support\Facades\Route;

$blockSkillPattern = implode('|', array_column(config('ai.skills.block_skills', []), 'skill'));

Route::redirect('/', '/sites')->name('home');

Route::get('preview/{site:slug}', [PreviewController::class, 'index'])->name('preview.index');
Route::get('preview/{site:slug}/{page:slug}', [PreviewController::class, 'show'])
    ->scopeBindings()
    ->name('preview.show');

Route::resource('sites', SiteController::class);
Route::resource('sites.pages', SitePageController::class)->except(['index'])->scoped(['page' => 'slug']);
Route::resource('sites.layouts', LayoutController::class)->only(['show'])->scoped(['layout' => 'id']);
Route::resource('pages.blocks', BlockPageController::class)->only(['create', 'store', 'edit', 'update', 'destroy'])->scoped(['page' => 'id']);
Route::post('pages/{page:id}/blocks/{block}/variants/{skill}', [BlockVariantAiController::class, 'storePage'])
    ->where('skill', $blockSkillPattern)
    ->name('pages.blocks.variants.store')
    ->scopeBindings();
Route::resource('layouts.blocks', LayoutBlockController::class)->only(['create', 'store', 'edit', 'update', 'destroy'])->scoped(['layout' => 'id']);
Route::post('layouts/{layout:id}/blocks/{block}/variants/{skill}', [BlockVariantAiController::class, 'storeLayout'])
    ->where('skill', $blockSkillPattern)
    ->name('layouts.blocks.variants.store')
    ->scopeBindings();

Route::patch('blocks/{block}/move/{direction}', BlockPageMoveController::class)
    ->whereIn('direction', BlockDirection::values())
    ->name('blocks.move');
