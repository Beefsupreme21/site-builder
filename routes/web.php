<?php

use App\Http\Controllers\ChatController;
use App\Http\Controllers\LeadController;
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

Route::post('/preview/{site:slug}/contact', [LeadController::class, 'store'])
    ->name('sites.contact.store');

Route::get('/leads', [LeadController::class, 'index'])->name('leads.index');

Route::resource('sites', SiteController::class);
Route::resource('sites.pages', SitePageController::class)
    ->except(['index', 'show'])
    ->scoped(['page' => 'slug']);
