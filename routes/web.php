<?php

use Illuminate\Support\Facades\Route;

// Root redirect
Route::get('/', function () {
    return redirect('/login');
});

// Direct Auth Views
Route::view('/login', 'auth.login')->name('login');
Route::view('/register', 'auth.register')->name('register');

// Direct Dashboard Route (No Auth Middleware for Now)
Route::get('/dashboard', function () {
    return view('app');
})->name('dashboard');

Route::get('/app', function () {
    return view('app');
});