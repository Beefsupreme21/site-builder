<?php

use App\Http\Controllers\SiteController;
use Illuminate\Support\Facades\Route;

Route::inertia('/', 'welcome')->name('home');

Route::get('/preview/{site:slug}', [SiteController::class, 'preview'])
    ->name('sites.preview');

Route::resource('sites', SiteController::class)->except(['show']);
