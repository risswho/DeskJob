<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProgressController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Dashboard utama (wajib login)
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

// Pengguna yang sudah login dan aktif
Route::middleware(['auth', 'active'])->group(function () {

    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])
        ->name('logout');

    // CRUD Category dan User khusus superadmin
    Route::middleware('role:superadmin')->group(function () {

        Route::resource('categories', CategoryController::class)
            ->except(['show']);

        Route::resource('users', UserController::class)
            ->except(['show']);

    });

    // Daftar tugas: semua user aktif
    Route::get('/tasks', [TaskController::class, 'index'])
        ->name('tasks.index');

    // CRUD tugas: khusus superadmin
    Route::middleware('role:superadmin')->group(function () {

        Route::get('/tasks/create', [TaskController::class, 'create'])
            ->name('tasks.create');

        Route::post('/tasks', [TaskController::class, 'store'])
            ->name('tasks.store');

    });

    // Detail tugas: semua user aktif
    Route::get('/tasks/{task}', [TaskController::class, 'show'])
        ->whereNumber('task')
        ->name('tasks.show');

    Route::middleware('role:superadmin')->group(function () {

        // Penugasan staff
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

    // Membuat progress: semua user login, akses diperiksa oleh Policy
    Route::post('/tasks/{task}/progress', [ProgressController::class, 'store'])
        ->whereNumber('task')
        ->name('tasks.progress.store');

    //mengedit progress
    Route::put('/progress/{progress}', [ProgressController::class, 'update'])
        ->name('progress.update');

    // Menghapus progress
    Route::delete('/progress/{progress}', [ProgressController::class, 'destroy'])
        ->name('progress.destroy');
    
    // Menambahkan lampiran ke progress
    Route::post('/progress/{progress}/attachments', [
        ProgressController::class,
        'addAttachments'
    ])->name('progress.attachments.store');

    // Menghapus satu lampiran
    Route::delete('/attachments/{attachment}', [
        ProgressController::class,
        'destroyAttachment'
    ])->name('progress.attachments.destroy');
});