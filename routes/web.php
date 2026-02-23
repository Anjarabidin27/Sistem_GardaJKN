<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Halaman Login (Public)
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

// Redirect root ke login
Route::get('/', function () {
    return redirect()->route('login');
});

// Halaman Admin (Protected by JS Check)
Route::prefix('admin')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard'); // Nanti kita buat file ini
    });
    
    Route::get('/members', function () {
        return view('admin.members.index'); // Nanti kita buat file ini
    });

    Route::get('/audit-logs', function () {
        return view('admin.audit_logs.index'); // New Web Route
    });
});

// Halaman Member (Protected by JS Check)
Route::prefix('member')->group(function () {
    Route::get('/profile', function () {
        return view('member.profile'); // Nanti kita buat file ini
    });
});

// Design Route (Tetap ada untuk referensi)
Route::get('/design/dashboard', function () {
    return view('design.dashboard');
});
