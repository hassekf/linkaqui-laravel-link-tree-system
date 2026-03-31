<?php

use App\Http\Controllers\ProductSearchController;
use App\Http\Controllers\ProfilePageController;
use App\Http\Controllers\TrackClickController;
use App\Http\Controllers\TrackProductClickController;
use Illuminate\Support\Facades\Route;

Route::get('/', ProfilePageController::class)->name('profile.show')->middleware('throttle:profile-view');
Route::post('/click/{link}', TrackClickController::class)->name('profile.click')->middleware('throttle:click-track');
Route::get('/product/search', ProductSearchController::class)->name('profile.product.search')->middleware('throttle:click-track');
Route::post('/product/{code}/click', TrackProductClickController::class)->name('profile.product.click')->middleware('throttle:click-track');
