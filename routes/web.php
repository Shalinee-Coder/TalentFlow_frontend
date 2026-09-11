<?php

use Illuminate\Support\Facades\Route;

// 1. Root direct login par bhejega
Route::get('/', function () {
    return redirect()->route('login');
});

// 2. Auth views
Route::view('/login', 'auth.login')->name('login');
Route::view('/register', 'auth.register')->name('register');

// 3. Admin / Main Dashboard Route (MISSING ROUTE FIX)
Route::get('/dashboard', function () {
    return view('app'); // Ya jo bhi aapka admin blade file ka naam hai
})->name('dashboard');

Route::get('/app', function () {
    return view('app');
});

// API Info
Route::get('/api-info', function () {
    return response()->json([
        'system' => 'TalentFlow – Recruitment & Resume Management API',
        'status' => 'online',
        'version' => '1.0.0',
    ]);
});