<?php

use App\Http\Controllers\Web\admin\AdminController;
use App\Http\Controllers\Web\admin\SuperAdminController;
use App\Http\Controllers\Web\authentications\LoginController;
use App\Http\Controllers\Web\authentications\RegisterController;
use App\Http\Controllers\Web\settings\SettingsController;
use App\Http\Controllers\Web\account\AccountController;
use App\Http\Controllers\Web\transaction\TransactionController;
use App\Http\Controllers\Web\transfers\TransfersController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Redirect root to login if not authenticated
Route::get('/', function () {
    return redirect()->route('login');
})->middleware('guest');

// Authentication routes
Route::get('/login', [LoginController::class, 'index'])->name('login')->middleware('guest');
Route::post('/login', [LoginController::class, 'authenticate'])->name('login.submit');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Registration routes
Route::get('/register', [RegisterController::class, 'index'])->name('register')->middleware('guest');
Route::post('/register', [RegisterController::class, 'store'])->name('register.submit');

// Protected routes
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        if (Auth::user()->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }
        return view('dashboard.index');
    })->name('dashboard');

    // Redirect root to dashboard when authenticated
    Route::get('/', function () {
        return redirect()->route('dashboard');
    });

    // Other dashboard related routes
    Route::get('/transactions', function () {
        return view('transactions.index');
    })->name('transactions');

    Route::get('/kyc', function () {
        return view('kyc.index');
    })->name('kyc');

    Route::get('/settings', function () {
        return view('settings.index');
    })->name('settings');

    // Settings routes
    Route::put('/settings/profile', [SettingsController::class, 'updateProfile'])->name('settings.profile.update');
    Route::put('/settings/password', [SettingsController::class, 'updatePassword'])->name('settings.password.update');
    Route::put('/settings/notifications', [SettingsController::class, 'updateNotifications'])->name('settings.notifications.update');
    Route::put('/settings/preferences', [SettingsController::class, 'updatePreferences'])->name('settings.preferences.update');

    // Account routes
    Route::get('/accounts', [AccountController::class, 'index'])->name('accounts');
    Route::get('/accounts/create', [AccountController::class, 'create'])->name('account.create');
    Route::post('/accounts', [AccountController::class, 'store'])->name('account.store');
    Route::get('/accounts/{id}', [AccountController::class, 'show'])->name('account.show');
    Route::get('/accounts/{id}/edit', [AccountController::class, 'edit'])->name('account.edit');
    Route::put('/accounts/{id}', [AccountController::class, 'update'])->name('account.update');
    Route::delete('/accounts/{id}', [AccountController::class, 'destroy'])->name('account.destroy');
    Route::get('/accounts/{id}/statistics', [AccountController::class, 'statistics'])->name('account.statistics');
    Route::get('/account/transactions', [AccountController::class, 'transactions'])->name('account.transactions');

    // Transaction routes
    Route::prefix('transactions')->name('transaction.')->group(function () {
        Route::get('/', [TransactionController::class, 'index'])->name('index');
        Route::get('/create', [TransactionController::class, 'create'])->name('create');
        Route::post('/', [TransactionController::class, 'store'])->name('store');
        Route::get('/{id}', [TransactionController::class, 'show'])->name('show');
    });

    // Transfers routes
    Route::get('/transfers', [TransfersController::class, 'index'])->name('transfers');
    Route::post('/transfers/internal', [TransfersController::class, 'internalTransfer'])->name('transfers.internal');
    Route::post('/transfers/domestic', [TransfersController::class, 'domesticTransfer'])->name('transfers.domestic');
    Route::post('/transfers/international', [TransfersController::class, 'internationalTransfer'])->name('transfers.international');

    // Admin routes
    Route::middleware(['auth'])->prefix('admin')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
        Route::get('/users', [AdminController::class, 'users'])->name('admin.users');
        Route::post('/users', [AdminController::class, 'storeUser'])->name('admin.users.store');
        Route::get('/users/{id}/edit', [AdminController::class, 'editUser'])->name('admin.users.edit');
        Route::put('/users/{id}', [AdminController::class, 'updateUser'])->name('admin.users.update');
        Route::delete('/users/{id}', [AdminController::class, 'deleteUser'])->name('admin.users.delete');

        // Transaction Group routes for admin
        Route::get('/transaction-groups', [AdminController::class, 'transactionGroups'])->name('admin.transaction-groups');
        Route::get('/transaction-groups/create', [AdminController::class, 'createTransactionGroup'])->name('admin.transaction-groups.create');
        Route::post('/transaction-groups', [AdminController::class, 'storeTransactionGroup'])->name('admin.transaction-groups.store');
        Route::get('/transaction-groups/{id}', [AdminController::class, 'showTransactionGroup'])->name('admin.transaction-groups.show');
        Route::get('/transaction-groups/{id}/edit', [AdminController::class, 'editTransactionGroup'])->name('admin.transaction-groups.edit');
        Route::put('/transaction-groups/{id}', [AdminController::class, 'updateTransactionGroup'])->name('admin.transaction-groups.update');
        Route::delete('/transaction-groups/{id}', [AdminController::class, 'destroyTransactionGroup'])->name('admin.transaction-groups.destroy');

        // Super Admin routes
        Route::get('/system-settings', [SuperAdminController::class, 'systemSettings'])->name('admin.system-settings');
        Route::put('/system-settings', [SuperAdminController::class, 'updateSystemSettings'])->name('admin.system-settings.update');
        Route::get('/audit-logs', [SuperAdminController::class, 'auditLogs'])->name('admin.audit-logs');
    });
});
