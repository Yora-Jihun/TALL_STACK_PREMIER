<?php

use App\Http\Controllers\ShowWorkspaceController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');

Route::view('/dashboard', 'dashboard')
    ->middleware('auth')
    ->name('dashboard');

Route::get('/w/{workspace:slug}', ShowWorkspaceController::class)
    ->middleware(['auth', 'member'])
    ->name('workspaces.show');
