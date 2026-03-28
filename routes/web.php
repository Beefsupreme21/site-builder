<?php

use App\Http\Controllers\LeadController;
use App\Http\Controllers\SiteController;
use Illuminate\Support\Facades\Route;

Route::inertia('/', 'welcome')->name('home');

Route::get('/preview/{site:slug}', [SiteController::class, 'preview'])
    ->name('sites.preview');

Route::post('/preview/{site:slug}/contact', [LeadController::class, 'store'])
    ->name('sites.contact.store');

Route::get('/leads', [LeadController::class, 'index'])->name('leads.index');

Route::resource('sites', SiteController::class)->except(['show']);
