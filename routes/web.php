<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Root redirect
Route::get('/', function () {
    return redirect()->route('modules.dashboard');
});

// Authentication Routes
require __DIR__ . '/auth.php';

// Internal Application Module Routes
require __DIR__ . '/modules.php';
