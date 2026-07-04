<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TrainingController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\MateriController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\QuizQuestionController;
use App\Http\Controllers\QuizAttemptController;
use App\Http\Controllers\SertifikatController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\TrainingRegistrationController;  // ← PERBAIKAN: Ganti RegistrationController
use App\Http\Controllers\CertificateController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

// ============================================================
// LANDING ROUTES
// ============================================================
Route::get('/', [LandingController::class, 'index'])->name('landing.index');
Route::get('/pelatihan', [LandingController::class, 'pelatihan'])->name('landing.pelatihan.index');
Route::get('/pelatihan/{id}', [LandingController::class, 'pelatihanDetail'])->name('landing.pelatihan.detail');
Route::get('/tentang', [LandingController::class, 'tentang'])->name('landing.tentang.index');
Route::get('/faq', [LandingController::class, 'faq'])->name('landing.faq.index');
Route::get('/kontak', [LandingController::class, 'kontak'])->name('landing.kontak.index');
Route::post('/kontak', [LandingController::class, 'kontakSend'])->name('landing.kontak.send');

/*
|--------------------------------------------------------------------------
| Dashboard
|--------------------------------------------------------------------------
*/
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

/*
|--------------------------------------------------------------------------
| Authenticated Routes - Profile (User biasa)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    
    // Profile Routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
    Route::post('/profile/avatar', [ProfileController::class, 'uploadAvatar'])->name('profile.avatar.upload');
    Route::delete('/profile/avatar', [ProfileController::class, 'removeAvatar'])->name('profile.avatar.remove');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/profile/statistics', [ProfileController::class, 'getStatistics'])->name('profile.statistics');

    // Sertifikat User
    Route::get('/sertifikat/my-certificates', [SertifikatController::class, 'userCertificates'])->name('sertifikat.user');
    Route::get('/sertifikat/{sertifikat}/download', [SertifikatController::class, 'download'])->name('sertifikat.download');
});

/*
|--------------------------------------------------------------------------
| Peserta Area
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->prefix('peserta')->name('peserta.')->group(function () {
    
    // Dashboard Peserta
    Route::get('/dashboard', [DashboardController::class, 'pesertaDashboard'])->name('dashboard');
    
    // Profile Peserta
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.index');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
    Route::post('/profile/avatar', [ProfileController::class, 'uploadAvatar'])->name('profile.avatar.upload');
    Route::delete('/profile/avatar', [ProfileController::class, 'removeAvatar'])->name('profile.avatar.remove');
    
    // ============================================================
    // TRAINING PESERTA
    // ============================================================
    Route::get('/trainings', [TrainingController::class, 'pesertaIndex'])->name('trainings.index');
    Route::get('/trainings/{training}', [TrainingController::class, 'pesertaShow'])->name('trainings.show');
    Route::post('/trainings/{training}/enroll', [TrainingController::class, 'enroll'])->name('trainings.enroll');
    Route::post('/trainings/{training}/complete', [TrainingController::class, 'complete'])->name('trainings.complete');
    Route::get('/trainings/{training}/progress', [TrainingController::class, 'progress'])->name('trainings.progress');
    Route::delete('/trainings/{training}/unenroll', [TrainingController::class, 'unenroll'])->name('trainings.unenroll');
    
    // ============================================================
    // MATERI PESERTA
    // ============================================================
    Route::get('/materi', [MateriController::class, 'pesertaIndex'])->name('materi.index');
    Route::get('/materi/{materi}', [MateriController::class, 'pesertaShow'])->name('materi.show');
    Route::get('/materi/{materi}/download/{index?}', [MateriController::class, 'pesertaDownload'])->name('materi.download');
    Route::post('/materi/{materi}/complete', [MateriController::class, 'markComplete'])->name('materi.complete');
    Route::get('/materi/{materi}/progress', [MateriController::class, 'getProgress'])->name('materi.progress');
    
    // ============================================================
    // QUIZ PESERTA
    // ============================================================
    Route::get('/quiz', [QuizController::class, 'pesertaIndex'])->name('quiz.index');
    Route::get('/quiz/{quiz}', [QuizController::class, 'pesertaShow'])->name('quiz.show');
    Route::post('/quiz/{quiz}/start', [QuizAttemptController::class, 'start'])->name('quiz.start');
    Route::post('/quiz/{quiz}/submit', [QuizAttemptController::class, 'submit'])->name('quiz.submit');
    Route::get('/quiz/{quiz}/result/{attempt}', [QuizAttemptController::class, 'result'])->name('quiz.result');
    Route::get('/quiz/{quiz}/attempts', [QuizAttemptController::class, 'userAttempts'])->name('quiz.attempts');
    
    // ============================================================
    // SERTIFIKAT PESERTA
    // ============================================================
    Route::get('/sertifikat', [SertifikatController::class, 'pesertaIndex'])->name('sertifikat.index');
    Route::get('/sertifikat/{sertifikat}', [SertifikatController::class, 'pesertaShow'])->name('sertifikat.show');
    Route::get('/sertifikat/{sertifikat}/download', [SertifikatController::class, 'download'])->name('sertifikat.download');
});

/*
|--------------------------------------------------------------------------
| Admin Area
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {

    // ============================================================
    // DASHBOARD
    // ============================================================
    Route::get('/dashboard', [DashboardController::class, 'adminDashboard'])->name('dashboard');

    // ============================================================
    // PROFILE ADMIN
    // ============================================================
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
    Route::post('/profile/avatar', [ProfileController::class, 'uploadAvatar'])->name('profile.avatar.upload');
    Route::delete('/profile/avatar', [ProfileController::class, 'removeAvatar'])->name('profile.avatar.remove');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/profile/statistics', [ProfileController::class, 'getStatistics'])->name('profile.statistics');

    // ============================================================
    // USERS MANAGEMENT
    // ============================================================
    Route::get('users/export', [UserController::class, 'export'])->name('users.export');
    Route::get('users/{user}/change-password', [UserController::class, 'showChangePasswordForm'])->name('users.change-password.form');
    Route::put('users/{user}/change-password', [UserController::class, 'changePassword'])->name('users.change-password');
    Route::patch('users/{user}/activate', [UserController::class, 'activate'])->name('users.activate');
    Route::patch('users/{user}/deactivate', [UserController::class, 'deactivate'])->name('users.deactivate');
    Route::resource('users', UserController::class);

    // ============================================================
    // TRAINING MANAGEMENT
    // ============================================================
    Route::resource('trainings', TrainingController::class);
    Route::get('trainings/{training}/participants', [TrainingController::class, 'participants'])->name('trainings.participants');
    Route::patch('trainings/{training}/status', [TrainingController::class, 'changeStatus'])->name('trainings.status');
    Route::get('trainings/{training}/export', [TrainingController::class, 'export'])->name('trainings.export');

    // ============================================================
    // KATEGORI MANAGEMENT
    // ============================================================
    Route::resource('kategori', KategoriController::class);
    Route::get('kategori/data', [KategoriController::class, 'getData'])->name('kategori.data');
    Route::post('kategori/bulk-delete', [KategoriController::class, 'bulkDelete'])->name('kategori.bulk-delete');

    // ============================================================
    // MATERI MANAGEMENT
    // ============================================================
    Route::resource('materi', MateriController::class);
    Route::get('materi/{materi}/download/{index?}', [MateriController::class, 'download'])->name('materi.download');
    Route::patch('materi/{materi}/status', [MateriController::class, 'changeStatus'])->name('materi.status');
    Route::post('materi/bulk-delete', [MateriController::class, 'bulkDelete'])->name('materi.bulk-delete');
    Route::get('materi/{materi}/preview/{index?}', [MateriController::class, 'preview'])->name('materi.preview');

    // ============================================================
    // QUIZ MANAGEMENT
    // ============================================================
    Route::resource('quiz', QuizController::class);
    Route::patch('quiz/{quiz}/status', [QuizController::class, 'changeStatus'])->name('quiz.status');
    Route::post('quiz/{quiz}/duplicate', [QuizController::class, 'duplicate'])->name('quiz.duplicate');
    Route::get('quiz/by-materi/{materiId}', [QuizController::class, 'getByMateri'])->name('quiz.by-materi');
    Route::get('quiz/by-training/{trainingId}', [QuizController::class, 'getByTraining'])->name('quiz.by-training');
    Route::post('quiz/bulk-delete', [QuizController::class, 'bulkDelete'])->name('quiz.bulk-delete');
    Route::post('quiz/reorder', [QuizController::class, 'reorder'])->name('quiz.reorder');

    // ============================================================
    // QUIZ QUESTIONS MANAGEMENT
    // ============================================================
    Route::prefix('quiz/{quiz}/questions')->name('quiz.questions.')->group(function () {
        Route::get('/', [QuizQuestionController::class, 'index'])->name('index');
        Route::get('/create', [QuizQuestionController::class, 'create'])->name('create');
        Route::post('/', [QuizQuestionController::class, 'store'])->name('store');
        Route::get('/{question}/edit', [QuizQuestionController::class, 'edit'])->name('edit');
        Route::put('/{question}', [QuizQuestionController::class, 'update'])->name('update');
        Route::delete('/{question}', [QuizQuestionController::class, 'destroy'])->name('destroy');
        
        // Bulk Delete
        Route::post('/bulk-delete', [QuizQuestionController::class, 'bulkDelete'])->name('bulk-delete');
        
        // Reorder Questions
        Route::post('/reorder', [QuizQuestionController::class, 'reorder'])->name('reorder');
        
        // Duplicate Question
        Route::post('/{question}/duplicate', [QuizQuestionController::class, 'duplicate'])->name('duplicate');
        
        // Export Questions
        Route::get('/export', [QuizQuestionController::class, 'export'])->name('export');
    });

    // ============================================================
    // QUIZ ATTEMPT MANAGEMENT
    // ============================================================
    Route::prefix('quiz-attempts')->name('quiz.attempt.')->group(function () {
        Route::get('/', [QuizAttemptController::class, 'index'])->name('index');
        Route::get('/create', [QuizAttemptController::class, 'create'])->name('create');
        Route::post('/', [QuizAttemptController::class, 'store'])->name('store');
        Route::get('/{attempt}', [QuizAttemptController::class, 'show'])->name('show');
        Route::get('/{attempt}/edit', [QuizAttemptController::class, 'edit'])->name('edit');
        Route::put('/{attempt}', [QuizAttemptController::class, 'update'])->name('update');
        Route::delete('/{attempt}', [QuizAttemptController::class, 'destroy'])->name('destroy');
        
        // Custom routes
        Route::post('/{attempt}/complete', [QuizAttemptController::class, 'completeAttempt'])->name('complete');
        Route::post('/bulk-delete', [QuizAttemptController::class, 'bulkDelete'])->name('bulk-delete');
        Route::get('/export', [QuizAttemptController::class, 'export'])->name('export');
        Route::get('/by-quiz/{quizId}', [QuizAttemptController::class, 'getByQuiz'])->name('by-quiz');
        Route::get('/by-user/{userId}', [QuizAttemptController::class, 'getByUser'])->name('by-user');
    });

    // ============================================================
    // SERTIFIKAT MANAGEMENT
    // ============================================================
    Route::resource('sertifikat', SertifikatController::class);
    Route::get('sertifikat/{sertifikat}/download', [SertifikatController::class, 'download'])->name('sertifikat.download');
    Route::patch('sertifikat/{sertifikat}/status', [SertifikatController::class, 'changeStatus'])->name('sertifikat.status');
    Route::post('sertifikat/bulk-delete', [SertifikatController::class, 'bulkDelete'])->name('sertifikat.bulk-delete');

    // ============================================================
    // REGISTRATION MANAGEMENT (PENDAFTARAN) - PERBAIKAN
    // ============================================================
    Route::prefix('pendaftaran')->name('pendaftaran.')->group(function () {
        Route::get('/', [TrainingRegistrationController::class, 'index'])->name('index');  // ← PERBAIKAN
        Route::get('/create', [TrainingRegistrationController::class, 'create'])->name('create');  // ← PERBAIKAN
        Route::post('/', [TrainingRegistrationController::class, 'store'])->name('store');  // ← PERBAIKAN
        Route::get('/{id}', [TrainingRegistrationController::class, 'show'])->name('show');  // ← PERBAIKAN
        Route::get('/{id}/edit', [TrainingRegistrationController::class, 'edit'])->name('edit');  // ← PERBAIKAN
        Route::put('/{id}', [TrainingRegistrationController::class, 'update'])->name('update');  // ← PERBAIKAN
        Route::delete('/{id}', [TrainingRegistrationController::class, 'destroy'])->name('destroy');  // ← PERBAIKAN
        Route::put('/{id}/approve', [TrainingRegistrationController::class, 'approve'])->name('approve');  // ← PERBAIKAN
        Route::put('/{id}/reject', [TrainingRegistrationController::class, 'reject'])->name('reject');  // ← PERBAIKAN
        Route::put('/{id}/cancel', [TrainingRegistrationController::class, 'cancel'])->name('cancel');  // ← PERBAIKAN
        Route::post('/bulk-approve', [TrainingRegistrationController::class, 'bulkApprove'])->name('bulk-approve');  // ← PERBAIKAN
        Route::get('/export', [TrainingRegistrationController::class, 'export'])->name('export');  // ← PERBAIKAN
    });

    // ============================================================
    // CERTIFICATES MANAGEMENT
    // ============================================================
    Route::prefix('certificates')->name('certificates.')->group(function () {
        Route::get('/', [CertificateController::class, 'index'])->name('index');
        Route::get('/export', [CertificateController::class, 'export'])->name('export');
        Route::get('/create/{registrationId}', [CertificateController::class, 'create'])->name('create');
        Route::post('/store/{registrationId}', [CertificateController::class, 'store'])->name('store');
        Route::post('/generate/{registrationId}', [CertificateController::class, 'generate'])->name('generate');
        Route::get('/{certificateId}', [CertificateController::class, 'show'])->name('show');
        Route::get('/{certificateId}/edit', [CertificateController::class, 'edit'])->name('edit');
        Route::put('/{certificateId}', [CertificateController::class, 'update'])->name('update');
        Route::delete('/{certificateId}', [CertificateController::class, 'destroy'])->name('destroy');
        Route::post('/{certificateId}/revoke', [CertificateController::class, 'revoke'])->name('revoke');
    });

    // ============================================================
    // REPORTS
    // ============================================================
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/', [ReportController::class, 'index'])->name('index');
        Route::get('/trainings', [ReportController::class, 'trainings'])->name('trainings');
        Route::get('/users', [ReportController::class, 'users'])->name('users');
        Route::get('/certificates', [ReportController::class, 'certificates'])->name('certificates');
        Route::get('/materi', [ReportController::class, 'materi'])->name('materi');
        Route::get('/quiz', [ReportController::class, 'quiz'])->name('quiz');
        Route::get('/export/{type}', [ReportController::class, 'export'])->name('export');
    });

    // ============================================================
    // SETTINGS
    // ============================================================
    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('/', [SettingController::class, 'index'])->name('index');
        Route::put('/general', [SettingController::class, 'updateGeneral'])->name('general');
        Route::put('/security', [SettingController::class, 'updateSecurity'])->name('security');
        Route::put('/notifications', [SettingController::class, 'updateNotifications'])->name('notifications');
    });

    // ============================================================
    // SEARCH
    // ============================================================
    Route::get('/search', [DashboardController::class, 'search'])->name('search');
});

require __DIR__.'/auth.php';