<?php

use App\Http\Controllers\Api\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Api\Admin\DashboardController;
use App\Http\Controllers\Api\Admin\MemberController as AdminMemberController;
use App\Http\Controllers\Api\Master\RegionController;
use App\Http\Controllers\Api\Member\AuthController as MemberAuthController;
use App\Http\Controllers\Api\Member\ProfileController;
use App\Http\Controllers\Api\Common\SettingsController;
use App\Http\Controllers\Api\Admin\BpjsKelilingController as AdminBpjsController;
use App\Http\Controllers\Api\Admin\PilController as AdminPilController;
use App\Http\Controllers\Api\Member\BpjsKelilingController as MemberBpjsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->group(function () {
    Route::prefix('settings')->controller(SettingsController::class)->group(function () {
        Route::get('profile', 'getProfile');
        Route::post('change-password', 'changePassword');
    });
});

// --- Public Master Data ---
Route::prefix('master')->controller(RegionController::class)->group(function () {
    Route::get('provinces', 'provinces');
    Route::get('cities', 'cities');
    Route::get('districts', 'districts');
});

// --- Member Portal ---
Route::prefix('member')->group(function () {
    Route::post('register', [MemberAuthController::class, 'register']);
    Route::post('login', [MemberAuthController::class, 'login']);

    Route::middleware(['auth:sanctum', 'role:anggota'])->group(function () {
        Route::post('logout', [MemberAuthController::class, 'logout']);
        Route::controller(ProfileController::class)->group(function () {
            Route::get('profile', 'show');
            Route::match(['put', 'post'], 'profile', 'update');
            Route::post('apply-pengurus', 'applyPengurus');
        });

        Route::prefix('informations')->controller(\App\Http\Controllers\Api\Member\InformationController::class)->group(function () {
            Route::get('/', 'index');
            Route::get('{id}', 'show');
        });

        // BPJS Keliling (Member, Read-Only)
        Route::prefix('bpjs-keliling')->controller(MemberBpjsController::class)->group(function () {
            Route::get('/', 'index');
            Route::get('{id}', 'show');
        });
    });

    // BPJS Keliling (Pengurus, Write)
    Route::middleware(['auth:sanctum', 'role:pengurus'])->group(function () {
        Route::prefix('bpjs-keliling')->controller(MemberBpjsController::class)->group(function () {
            Route::post('/', 'store');
            Route::post('{id}/participants', 'addParticipant');
        });

        Route::prefix('pil')->controller(\App\Http\Controllers\Api\Member\PilController::class)->group(function () {
            Route::get('/', 'index');
            Route::post('/', 'store');
            Route::get('{id}', 'show');
            Route::post('{id}/participants', 'addParticipant');
        });
    });
});


// --- Admin Panel ---
Route::prefix('admin')->group(function () {
    Route::post('login', [AdminAuthController::class, 'login']);

    Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {
        Route::post('logout', [AdminAuthController::class, 'logout']);
        
        Route::get('dashboard', [DashboardController::class, 'index']);
        Route::get('audit-logs', [\App\Http\Controllers\Api\Admin\AuditLogController::class, 'index']);

        Route::prefix('members')->controller(AdminMemberController::class)->group(function () {
            Route::get('/', 'index');
            Route::post('/', 'store');
            Route::get('{id}', 'show');
            Route::put('{id}', 'update');
            Route::delete('{id}', 'destroy');
            Route::post('{id}/reset-password', 'resetPassword');
            Route::post('{id}/restore', 'restore');
            Route::delete('{id}/permanently-delete', 'permanentlyDelete');
            Route::post('{id}/verify-pengurus', 'verifyPengurus');
        });

        Route::prefix('informations')->controller(\App\Http\Controllers\Api\Admin\InformationController::class)->group(function () {
            Route::get('/', 'index');
            Route::post('/', 'store');
            Route::get('{id}', 'show');
            Route::match(['put', 'post'], '{id}', 'update');
            Route::delete('{id}', 'destroy');
            Route::patch('{id}/toggle-status', 'toggleStatus');
        });

        Route::prefix('bpjs-keliling')->controller(AdminBpjsController::class)->group(function () {
            Route::get('dashboard', 'dashboard');
            Route::get('/', 'index');
            Route::post('/', 'store');
            Route::get('{id}', 'show');
            Route::put('{id}', 'update');
            Route::delete('{id}', 'destroy');
            Route::post('{id}/laporan', 'storeLaporan');
        });

        Route::prefix('pil')->controller(AdminPilController::class)->group(function () {
            Route::get('dashboard', 'dashboard');
            Route::get('/', 'index');
            Route::post('/', 'store');
            Route::get('{id}', 'show');
            Route::put('{id}', 'update');
            Route::delete('{id}', 'destroy');
            Route::post('{id}/laporan', 'storeLaporan');
        });
    });
});
