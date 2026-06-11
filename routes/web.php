<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AdminAuthController;
use App\Http\Controllers\Auth\StudentAuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\Admin\PostController;
use App\Http\Controllers\Admin\CandidateController;
use App\Http\Controllers\Admin\ResultController;
use App\Http\Controllers\Admin\ElectionSettingController;
use App\Http\Controllers\Admin\StudentBulkImportController;
use App\Http\Controllers\Student\VotingController;
use App\Http\Controllers\Student\ResultController as StudentResultController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| College Voting System routes for admin panel, student authentication
| via OTP, voting, and results viewing.
|
*/

// Root redirect to student login
Route::get('/', fn () => redirect('/login'));

// ─── Admin Authentication ────────────────────────────────────────────
Route::get('/admin/login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
Route::post('/admin/login', [AdminAuthController::class, 'login'])->name('admin.login.submit');
Route::post('/admin/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

// ─── Admin Panel (protected by auth + admin middleware) ──────────────
Route::prefix('admin')->middleware(['auth', 'admin'])->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Student bulk import — must be declared BEFORE the resource route
    // so Laravel doesn't treat 'bulk-import' as a {student} parameter.
    Route::get('/students/bulk-import',          [StudentBulkImportController::class, 'index'])    ->name('students.bulk-import');
    Route::post('/students/bulk-import/preview', [StudentBulkImportController::class, 'preview'])  ->name('students.bulk-import.preview');
    Route::post('/students/bulk-import/store',   [StudentBulkImportController::class, 'store'])    ->name('students.bulk-import.store');
    Route::get('/students/bulk-import/template', [StudentBulkImportController::class, 'template']) ->name('students.bulk-import.template');
    Route::delete('/students/bulk-delete', [StudentController::class, 'bulkDestroy'])->name('students.bulk-destroy');

    Route::resource('students', StudentController::class);
    Route::resource('posts', PostController::class);
    Route::resource('candidates', CandidateController::class);

    Route::get('/results', [ResultController::class, 'index'])->name('results.index');
    Route::get('/results/{post}', [ResultController::class, 'postDetail'])->name('results.post');
    Route::post('/results/publish', [ResultController::class, 'publish'])->name('results.publish');
    Route::post('/results/unpublish', [ResultController::class, 'unpublish'])->name('results.unpublish');

    Route::get('/settings', [ElectionSettingController::class, 'edit'])->name('settings.edit');
    Route::put('/settings', [ElectionSettingController::class, 'update'])->name('settings.update');
    Route::post('/settings/toggle-voting', [ElectionSettingController::class, 'toggleVoting'])->name('settings.toggle-voting');
});

// ─── Student Authentication (OTP-based) ──────────────────────────────
Route::get('/login', [StudentAuthController::class, 'showLoginForm'])->name('student.login');
Route::post('/login/send-otp', [StudentAuthController::class, 'sendOtp'])->name('student.send-otp');
Route::get('/login/otp', [StudentAuthController::class, 'showOtpForm'])->name('student.otp');
Route::post('/login/verify-otp', [StudentAuthController::class, 'verifyOtp'])->name('student.verify-otp');
Route::post('/student/logout', [StudentAuthController::class, 'logout'])->name('student.logout');

// ─── Student Portal (protected by student middleware) ────────────────
Route::middleware(['student'])->group(function () {
    Route::middleware(['voting.open'])->group(function () {
        Route::get('/vote', [VotingController::class, 'index'])->name('student.vote');
        Route::post('/vote/cast', [VotingController::class, 'castVote'])->name('student.cast-vote');
    });
    Route::get('/thank-you', [VotingController::class, 'thankYou'])->name('student.thank-you');
    Route::get('/results', [StudentResultController::class, 'index'])->name('student.results');
});
