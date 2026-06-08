<?php

use App\Http\Controllers\Admin\DefenseController;
use App\Http\Controllers\Admin\ThesisController as AdminThesisController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ThesisController;
use App\Http\Controllers\ThesisVersionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', DashboardController::class)->name('dashboard');
    Route::get('/theses/create', [ThesisController::class, 'create'])->middleware('role:student')->name('theses.create');
    Route::post('/theses', [ThesisController::class, 'store'])->middleware('role:student')->name('theses.store');
    Route::get('/theses/{thesis}', [ThesisController::class, 'show'])->name('theses.show');
    Route::patch('/theses/{thesis}/status', [ThesisController::class, 'updateStatus'])->middleware('role:professor,admin')->name('theses.status.update');
    Route::post('/theses/{thesis}/versions', [ThesisVersionController::class, 'store'])->middleware('role:student')->name('thesis-versions.store');
    Route::get('/theses/{thesis}/versions/{version}/download', [ThesisVersionController::class, 'download'])->name('thesis-versions.download');
    Route::post('/theses/{thesis}/feedback', [FeedbackController::class, 'store'])->middleware('role:professor')->name('feedback.store');
    Route::patch('/feedback/{feedback}/resolve', [FeedbackController::class, 'resolve'])->middleware('role:student')->name('feedback.resolve');
    Route::get('/theses/{thesis}/chat', [ChatController::class, 'show'])->name('chat.show');
    Route::post('/theses/{thesis}/chat', [ChatController::class, 'store'])->name('chat.store');
    Route::post('/notifications/read', function (Request $request) { $request->user()->unreadNotifications->markAsRead(); return back(); })->name('notifications.read');

    Route::prefix('admin')->name('admin.')->middleware('role:admin')->group(function () {
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::post('/users', [UserController::class, 'store'])->name('users.store');
        Route::patch('/users/{user}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
        Route::get('/theses', [AdminThesisController::class, 'index'])->name('theses.index');
        Route::patch('/theses/{thesis}/assign', [AdminThesisController::class, 'assign'])->name('theses.assign');
        Route::patch('/theses/{thesis}/archive', [AdminThesisController::class, 'archive'])->name('theses.archive');
        Route::get('/defenses', [DefenseController::class, 'index'])->name('defenses.index');
        Route::put('/defenses/{thesis}', [DefenseController::class, 'store'])->name('defenses.store');
    });
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
