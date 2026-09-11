<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;

// 1. Temporary Route to Seed Database
Route::get('/force-seed', function () {
    Artisan::call('db:seed', ['--force' => true]);
    return 'Database Seeded Successfully! Now go to /login';
});

// 2. Root Redirect
Route::get('/', function () {
    return redirect()->route('login');
});

// 3. Auth Routes
Route::view('/login', 'auth.login')->name('login');
Route::view('/register', 'auth.register')->name('register');

// 4. Dashboard Route
Route::get('/dashboard', function () {
    return view('app');
})->name('dashboard');

Route::get('/app', function () {
    return view('app');
});