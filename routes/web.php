<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::view('/login', 'auth.login')->name('login');
Route::view('/register', 'auth.register')->name('register');

Route::get('/api-info', function () {
    return response()->json([
        'system' => 'TalentFlow – Recruitment & Resume Management API',
        'status' => 'online',
        'version' => '1.0.0',
        'api_documentation' => '/docs/API.md',
    ]);
});

