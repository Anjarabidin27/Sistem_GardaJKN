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

// Settings (Separated per role for 100% consistency)
Route::get('/admin/settings', function () { return view('admin.settings'); })->name('admin.settings');
Route::get('/pengurus/settings', function () { return view('pengurus.settings'); })->name('pengurus.settings');
Route::get('/member/settings', function () { return view('member.settings'); })->name('member.settings');

// Legacy redirect if needed (optional, we can also remove it)
Route::get('/settings', function () {
    return "<script>
        const role = localStorage.getItem('user_role');
        if(role === 'admin') window.location.href='/admin/settings';
        else if(role === 'pengurus') window.location.href='/pengurus/settings';
        else if(role === 'member') window.location.href='/member/settings';
        else window.location.href='/login';
    </script>";
});

// Halaman Admin (Protected by JS Check / Token)
Route::prefix('admin')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard'); // Nanti kita buat file ini
    });
    
    Route::get('/members', function () {
        return view('admin.members.index');
    });

    Route::get('/staff', function () {
        return view('admin.staff.index');
    });

    Route::get('/audit-logs', function () {
        return view('admin.audit_logs.index'); // New Web Route
    });

    Route::prefix('bpjs-keliling')->group(function () {
        Route::get('/', function () { return view('admin.bpjs_keliling.index'); });
        Route::get('/laporan', function () { return view('admin.bpjs_keliling.laporan'); });
        Route::get('/dashboard', function () { return view('admin.bpjs_keliling.dashboard'); });
    });

    Route::prefix('pil')->group(function () {
        Route::get('/', function () { return view('admin.pil.index'); });
        Route::get('/dashboard', function () { return view('admin.pil.dashboard'); });
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

// Halaman Member (Protected by JS Check / Token)
Route::prefix('member')->group(function () {
    Route::get('/profile', function () {
        return view('member.profile'); // Nanti kita buat file ini
    });

    Route::get('/informations', function () {
        return view('member.informations.index');
    });

    Route::get('/bpjs-keliling', function () {
        return view('member.bpjs_keliling.index');
    });
});

// Halaman Pengurus (Protected by JS Check / Token)
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
