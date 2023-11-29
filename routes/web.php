<?php

use App\Http\Controllers\GradeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\SectionController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

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


Route::get('/home', function () {
    return view('home');
});

Route::get('/grade',[GradeController::class,'index'])->name('grade.index');
Route::get('/grade/create/',[GradeController::class,'create'])->name('grade.create');
Route::post('/grade/store/',[GradeController::class,'store'])->name('grade.store');
Route::get('/grade/{id}/edit',[GradeController::class,'edit'])->name('grade.edit');
Route::put('/grade/{id}/update',[GradeController::class,'update'])->name('grade.update');
Route::get('/grade/{id}/delete',[GradeController::class,'delete'])->name('grade.delete');


Route::get('/user',[UserController::class,'index'])->name('user.index');
Route::get('/user/create/',[UserController::class,'create'])->name('user.create');
Route::post('/user/store/',[UserController::class,'store'])->name('user.store');
Route::get('/user/{id}/edit',[UserController::class,'edit'])->name('user.edit');
Route::put('/user/{id}/update',[UserController::class,'update'])->name('user.update');
Route::get('/user/{id}/delete',[UserController::class,'delete'])->name('user.delete');


Route::get('/student',[StudentController::class,'index'])->name('student.index');
Route::get('/student/create/',[StudentController::class,'create'])->name('student.create');
Route::post('/student/store/',[StudentController::class,'store'])->name('student.store');
Route::get('/student/{id}/edit',[StudentController::class,'edit'])->name('student.edit');
Route::put('/student/{id}/update',[StudentController::class,'update'])->name('student.update');
Route::get('/student/{id}/delete',[StudentController::class,'delete'])->name('student.delete');

Route::get('/section',[SectionController::class,'index'])->name('section.index');
Route::get('/section/create/',[SectionController::class,'create'])->name('section.create');
Route::post('/section/store/',[SectionController::class,'store'])->name('section.store');
Route::get('/section/{id}/edit',[SectionController::class,'edit'])->name('section.edit');
Route::put('/section/{id}/update',[SectionController::class,'update'])->name('section.update');
Route::get('/section/{id}/delete',[SectionController::class,'delete'])->name('section.delete');
