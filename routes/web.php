<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\AssistanceTeacherController;
use App\Http\Controllers\PeriodController;
use App\Http\Controllers\CommentController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Illuminate\Support\Facades\Gate;

Route::get('/', function () {
    //return Inertia::render('welcome');
    return redirect(route('login'));
})->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', function () {
        //return Inertia::render('dashboard');
        if (!Gate::allows('manage-assistance'))
            return Inertia::location(route('user.create_assistance', Auth::id()));
        else return Inertia::location(route('assistance_teacher'));
    })->name('dashboard');

    Route::get('/user', [UserController::class, 'index'])->name('user');
    Route::get('/user/create', [UserController::class, 'create'])->name('user.create');
    Route::post('/user/store', [UserController::class, 'store'])->name('user.store');
    Route::get('/user/{user}/edit', [UserController::class, 'edit'])->name('user.edit');
    Route::put('/user/{user}/update', [UserController::class, 'update'])->name('user.update');
    Route::get('/user/{user}', [UserController::class, 'show'])->name('user.show');
    Route::get('/assistanceteacher/{user}/create', [UserController::class, 'create_assistance'])->name('user.create_assistance');
    Route::get('/teacher-submitted/{user}', [UserController::class, 'submitted'])->name('user.submitted');
    Route::delete('/user/{user}/destroy', [UserController::class, 'destroy'])->name('user.destroy');


    Route::get('/assistanceteacher', [AssistanceTeacherController::class, 'index'])->name('assistance_teacher');
    Route::get('/assistanceteacher/create', [AssistanceTeacherController::class, 'create'])->name('assistance_teacher.create');
    Route::post('/assistanceteacher/confirm', [AssistanceTeacherController::class, 'confirm'])->name('assistance_teacher.confirm');
    Route::post('/assistanceteacher/store', [AssistanceTeacherController::class, 'store'])->name('assistance_teacher.store');
    Route::get('/assistanceteacher/{assistanceTeacher}', [AssistanceTeacherController::class, 'show'])->name('assistance_teacher.show');
    Route::get('/assistanceteacher/{assistanceTeacher}/edit', [AssistanceTeacherController::class, 'edit'])->name('assistance_teacher.edit');
    Route::put('/assistanceteacher/{assistanceTeacher}/update', [AssistanceTeacherController::class, 'update'])->name('assistance_teacher.update');
    Route::delete('/assistanceteacher/{assistance_teacher}/destroy', [AssistanceTeacherController::class, 'destroy'])->name('assistance_teacher.destroy');
    Route::delete('/assistanceteacher/destroymany', [AssistanceTeacherController::class, 'destroy_many'])->name('assistance_teacher.destroy_many');


    Route::get('/assistances-export', [AssistanceTeacherController::class, 'export'])->name('assistance_teacher.export');
    Route::get('/assistances-export-by-range/{ini?}/{end?}', [AssistanceTeacherController::class, 'export_by_range'])->name('assistance_teacher.export_by_range');
    Route::get('/assistances-export-by-date/{date?}', [AssistanceTeacherController::class, 'export_by_date'])->name('assistance_teacher.export_by_date');
    Route::post('/assistance-comment-ajax', [AssistanceTeacherController::class, 'assistance_comment_ajax'])->name('assistance_teacher.assistance_comment_ajax');
    Route::post('/assistance-select-teacher-ajax', [AssistanceTeacherController::class, 'select_teacher_ajax'])->name('assistance_teacher.select_teacher_ajax');
    Route::post('/assistance-temp-store-ajax', [AssistanceTeacherController::class, 'temp_store_ajax'])->name('assistance_teacher.temp_store_ajax');

    Route::get('/assistances-export/{user}', [UserController::class, 'export'])->name('user.export');
    Route::get('/assistances-export-by-range/{user}/{ini?}/{end?}', [UserController::class, 'export_by_range'])->name('user.export_by_range');
    Route::get('/assistances-export-by-date/{user}/{date?}', [UserController::class, 'export_by_date'])->name('user.export_by_date');

    Route::get('/comments', [CommentController::class, 'index'])->name('comments');

    Route::get('/period', [PeriodController::class, 'index'])->name('period');


    /*Route::get('/user', [UserController::class, 'index'])->name('user');
    Route::get('/user/{user}/edit', [UserController::class, 'edit'])->name('user.edit');
    Route::put('/user/{user}/update', [UserController::class, 'update'])->name('user.update');*/
    Route::get('/user/{user}/reset', [UserController::class, 'reset_password'])->name('user.reset_password');

});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
