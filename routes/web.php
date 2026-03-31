<?php

use App\Livewire\Admin\Dashboard as AdminDashboard;
use App\Livewire\Admin\ThemeManager;
use App\Livewire\Admin\UserDetail;
use App\Livewire\Admin\UserManager;
use App\Livewire\Builder\Analytics;
use App\Livewire\Builder\PageBuilder;
use App\Livewire\Builder\Products;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('builder');
    }

    return view('landing');
})->name('home');

Route::middleware(['auth', 'active', 'verified'])->group(function () {
    Route::redirect('dashboard', '/builder')->name('dashboard');
    Route::get('builder', PageBuilder::class)->name('builder');
    Route::get('analytics', Analytics::class)->name('analytics');
    Route::get('products', Products::class)->name('products');
});

Route::middleware(['auth', 'active', 'verified', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', AdminDashboard::class)->name('dashboard');
    Route::get('/users', UserManager::class)->name('users');
    Route::get('/users/{user}', UserDetail::class)->name('users.show');
    Route::get('/themes', ThemeManager::class)->name('themes');
});

require __DIR__.'/settings.php';
