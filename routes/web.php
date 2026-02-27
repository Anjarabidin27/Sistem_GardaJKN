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

// Halaman Login (Public - Member & Pengurus)
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

// Halaman Login Admin (Private)
Route::get('/login/admin', function () {
    return view('auth.admin_login');
})->name('admin.login');

Route::get('/register', function () {
    return view('auth.register');
})->name('register');

// Redirect root ke login
Route::get('/', function () {
    return redirect()->route('login');
});

// Settings (Common for All Roles)
Route::get('/settings', function () {
    return view('common.settings');
})->name('settings');

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

    Route::get('/informations', function () {
        return view('admin.informations.index');
    });

    // Approval Section
    Route::prefix('approvals')->name('admin.approvals.')->controller(\App\Http\Controllers\Web\AdminApprovalController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/{id}/approve', 'approve')->name('approve');
        Route::post('/{id}/reject', 'reject')->name('reject');
    });
});

// Halaman Member (Protected by JS Check)
Route::prefix('member')->group(function () {
    Route::get('/profile', function () {
        return view('member.profile'); // Nanti kita buat file ini
    });

    Route::get('/informations', function () {
        return view('member.informations.index');
    });
});

// Halaman Pengurus (Protected by JS Check)
Route::prefix('pengurus')->group(function () {
    Route::get('/dashboard', function () {
        return view('pengurus.dashboard');
    });

    Route::get('/members', function () {
        return view('pengurus.members');
    });

    Route::get('/informations', function () {
        return view('pengurus.informations');
    });
});

// Design Route (Tetap ada untuk referensi)
Route::get('/design/dashboard', function () {
    return view('design.dashboard');
});
