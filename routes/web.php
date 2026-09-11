<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

// Root redirect
Route::get('/', function () {
    return redirect('/login');
});

// Direct Auth Views
Route::view('/login', 'auth.login')->name('login');
Route::view('/register', 'auth.register')->name('register');

// Form Submit Handler (Fixes Pending/Loop Issue)
Route::post('/login', function (Request $request) {
    return redirect('/dashboard');
});

Route::post('/register', function (Request $request) {
    return redirect('/dashboard');
});

// Direct Dashboard Route
Route::get('/dashboard', function () {
    return view('app');
})->name('dashboard');

Route::get('/app', function () {
    return view('app');
});