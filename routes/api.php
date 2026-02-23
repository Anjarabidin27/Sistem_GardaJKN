<?php

use App\Http\Controllers\Api\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Api\Admin\DashboardController;
use App\Http\Controllers\Api\Admin\MemberController as AdminMemberController;
use App\Http\Controllers\Api\Master\RegionController;
use App\Http\Controllers\Api\Member\AuthController as MemberAuthController;
use App\Http\Controllers\Api\Member\ProfileController;
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

// --- Public Master Data ---
Route::prefix('master')->controller(RegionController::class)->group(function () {
    Route::get('provinces', 'provinces');
    Route::get('cities', 'cities');
    Route::get('districts', 'districts');
});

// --- Member Portal ---
Route::prefix('member')->group(function () {
    Route::post('login', [MemberAuthController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('logout', [MemberAuthController::class, 'logout']);
        Route::controller(ProfileController::class)->group(function () {
            Route::get('profile', 'show');
            Route::put('profile', 'update');
        });
    });
});

// --- Admin Panel ---
Route::prefix('admin')->group(function () {
    Route::post('login', [AdminAuthController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {
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
        });
    });
});
