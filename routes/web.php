<?php

use App\Http\Controllers\ArchiveController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GradeController;
use App\Http\Controllers\PasswordChangeController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SectionController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TeacherDashboardController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    if (Auth::check()) {
        return redirect('/home');
    }

    return view('auth.login');
});

Route::group(['middleware' => ['role:admin', 'preventBackHistory']], function () {
    Route::get('/home', [DashboardController::class, 'index'])->name('admin.index');

    Route::get('/grade', [GradeController::class, 'index'])->name('grade.index');
    Route::get('/grade/create/', [GradeController::class, 'create'])->name('grade.create');
    Route::post('/grade/store/', [GradeController::class, 'store'])->name('grade.store');
    Route::get('/grade/{id}/edit', [GradeController::class, 'edit'])->name('grade.edit');
    Route::put('/grade/{id}/update', [GradeController::class, 'update'])->name('grade.update');
    Route::get('/grade/{id}/delete', [GradeController::class, 'delete'])->name('grade.delete');

    Route::get('/user', [UserController::class, 'index'])->name('user.index');
    Route::get('/user/create/', [UserController::class, 'create'])->name('user.create');
    Route::post('/user/store/', [UserController::class, 'store'])->name('user.store');
    Route::get('/user/{id}/edit', [UserController::class, 'edit'])->name('user.edit');
    Route::put('/user/{id}/update', [UserController::class, 'update'])->name('user.update');
    Route::get('/user/{id}/delete', [UserController::class, 'delete'])->name('user.delete');

    Route::get('/student', [StudentController::class, 'index'])->name('student.index');
    Route::get('/student/create/', [StudentController::class, 'create'])->name('student.create');
    Route::post('/student/store/', [StudentController::class, 'store'])->name('student.store');
    Route::get('/student/{id}/edit', [StudentController::class, 'edit'])->name('student.edit');
    Route::put('/student/{id}/update', [StudentController::class, 'update'])->name('student.update');
    Route::get('/student/{id}/delete', [StudentController::class, 'delete'])->name('student.delete');
    Route::get('/student/bulk', [StudentController::class, 'getBulkUpload'])->name('student.getBulkUpload');
    Route::post('/student/bulk', [StudentController::class, 'bulkUpload'])->name('student.bulkUpload');
    Route::get('/student/bulk-sample-download', [StudentController::class, 'bulkSample'])->name('student.bulkSample');

    Route::get('/section', [SectionController::class, 'index'])->name('section.index');
    Route::get('/section/create/', [SectionController::class, 'create'])->name('section.create');
    Route::post('/section/store/', [SectionController::class, 'store'])->name('section.store');
    Route::get('/section/{id}/edit', [SectionController::class, 'edit'])->name('section.edit');
    Route::put('/section/{id}/update', [SectionController::class, 'update'])->name('section.update');
    Route::get('/section/{id}/delete', [SectionController::class, 'delete'])->name('section.delete');

    // report
    Route::get('/report', [ReportController::class, 'adminIndex'])->name('report.index');
    Route::get('/report/gradeSearch', [ReportController::class, 'gradeSearch'])->name('report.gradeSearch');
    Route::get('/report/search', [ReportController::class, 'adminSearch'])->name('report.search');
    Route::post('/admin-report/download', [ReportController::class, 'adminReportDownload'])->name('admin-report.download');
    // attendance edit
    Route::post('/admin-attendance', [AttendanceController::class, 'store'])->name('admin.attendance.store');
    Route::get('/today-attendance', [AttendanceController::class, 'index'])->name('attendance.index');
    Route::get('/today-attendance/take-attendance', [AttendanceController::class, 'adminAttendanceIndex'])->name('attendance.takeAttendance');
    Route::get('/attendance/{user}/edit', [AttendanceController::class, 'edit'])->name('attendance.edit');
    Route::put('/attendance/{user}', [AttendanceController::class, 'update'])->name('attendance.update');

    Route::get('/grade/archive/{id}', [ArchiveController::class, 'archive'])->name('archive-grade');
    Route::get('/archive', [ArchiveController::class, 'show_all_archive'])->name('show-archive');

    Route::get('/archive/search', [ArchiveController::class, 'search'])->name('archive.search');

});

Route::group(['middleware' => ['role:teacher', 'password.change', 'preventBackHistory']], function () {
    // dashboard
    Route::get('/dashboard', [TeacherDashboardController::class, 'dashboard'])->name('teacher.dashboard');
    // attendance
    Route::get('/attendance', [AttendanceController::class, 'create'])->name('attendance.create');
    Route::post('/attendance', [AttendanceController::class, 'store'])->name('attendance.store');
    // report
    Route::get('/teacher-report', [ReportController::class, 'teacherIndex'])->name('teacher-report.index');
    Route::get('/teacher-report/search', [ReportController::class, 'teacherSearch'])->name('teacher-report.search');
    Route::post('/teacher-report/download', [ReportController::class, 'teacherReportDownload'])->name('teacher-report.download');
});

// password change
Route::get('/change-password', [PasswordChangeController::class, 'index'])->name('change-password');
