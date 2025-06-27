<?php

use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SubjectController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// -------------new routes----------------

// attendence marking form
Route::get('/mark-attendance',[AttendanceController::class, 'markAttendanceForm'])->name('attendances.mark.form');
// handle form submission
Route::post('/attendances', [AttendanceController::class, 'store'])->name('attendances.store');

// teacher subjects api endpoints
Route::get('/api/teacher-subjects', [SubjectController::class, 'getTeacherSubjects'])->name('api.teacher.subjects');
// get students for a subject
Route::get('/api/subjects/{subject}/students', [SubjectController::class, 'getStudentsForSubject'])->name('api.subjects.students');
// get all subjects
Route::get('/api/all-subjects', [SubjectController::class, 'getAllSubjects'])->name('api.all.subjects');

// attendance dashboard
Route::get('/attendance-dashboard', [AttendanceController::class, 'dashboard'])->name('attendances.dashboard');


require __DIR__.'/auth.php';
