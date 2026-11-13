<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProgressController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Dashboard
Route::get('/', [DashboardController::class, 'index'])
    ->middleware(['auth', 'active'])
    ->name('dashboard');

// Guest
Route::middleware('guest')->group(function () {

    Route::get('/login', [AuthController::class, 'showLogin'])
        ->name('login');

    Route::post('/login', [AuthController::class, 'login'])
        ->middleware('throttle:5,1')
        ->name('login.store');

});

// Pengguna login dan aktif
Route::middleware(['auth', 'active'])->group(function () {

    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])
        ->name('logout');

    // Category dan User khusus superadmin
    Route::middleware('role:superadmin')->group(function () {

        Route::resource('categories', CategoryController::class)
            ->except(['show']);

        Route::resource('users', UserController::class)
            ->except(['show']);

    });

    // Daftar tugas
    Route::get('/tasks', [TaskController::class, 'index'])
        ->name('tasks.index');

    // Membuat tugas
    Route::middleware('role:superadmin')->group(function () {

        Route::get('/tasks/create', [TaskController::class, 'create'])
            ->name('tasks.create');

        Route::post('/tasks', [TaskController::class, 'store'])
            ->name('tasks.store');

    });

    // Detail tugas
    Route::get('/tasks/{task}', [TaskController::class, 'show'])
        ->whereNumber('task')
        ->name('tasks.show');

    // Mengelola tugas
    Route::middleware('role:superadmin')->group(function () {

        Route::put('/tasks/{task}/assign', [TaskController::class, 'assignStaff'])
            ->whereNumber('task')
            ->name('tasks.assign');

        Route::get('/tasks/{task}/edit', [TaskController::class, 'edit'])
            ->whereNumber('task')
            ->name('tasks.edit');

        Route::put('/tasks/{task}', [TaskController::class, 'update'])
            ->whereNumber('task')
            ->name('tasks.update');

        Route::delete('/tasks/{task}', [TaskController::class, 'destroy'])
            ->whereNumber('task')
            ->name('tasks.destroy');

    });

    // Progress
    Route::post('/tasks/{task}/progress', [ProgressController::class, 'store'])
        ->whereNumber('task')
        ->name('tasks.progress.store');

    Route::put('/progress/{progress}', [ProgressController::class, 'update'])
        ->name('progress.update');

    Route::delete('/progress/{progress}', [ProgressController::class, 'destroy'])
        ->name('progress.destroy');

    // Menambahkan lampiran
    Route::post('/progress/{progress}/attachments', [
        ProgressController::class,
        'addAttachments'
    ])->name('progress.attachments.store');

    // Menghapus lampiran
    Route::delete('/attachments/{attachment}', [
        ProgressController::class,
        'destroyAttachment'
    ])->name('progress.attachments.destroy');

    // Melihat lampiran (WAJIB LOGIN)
    Route::get('/attachments/{attachment}/view', [
        ProgressController::class,
        'showAttachment'
    ])->name('attachments.view');

    // Mengunduh lampiran (WAJIB LOGIN)
    Route::get('/attachments/{attachment}/download', [
        ProgressController::class,
        'downloadAttachment'
    ])->name('attachments.download');

});
