<?php

use App\Http\Controllers\Settings\ClinicBranchController;
use App\Http\Controllers\Settings\DepartmentController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth.login');
});

Route::get('/formclinic', function () {
    return view('forms.clinic_form');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/clinic', [ClinicBranchController::class, 'index'])->name('settings.clinic');
Route::get('/department', [DepartmentController::class, 'index'])->name('settings.department');
Route::post('/department/store', [DepartmentController::class, 'store'])->name('settings.department.store');
Route::get('/department/{department}/edit', [DepartmentController::class, 'edit'])->name('settings.department.edit');
Route::post('/department/update', [DepartmentController::class, 'update'])->name('settings.department.update');
Route::delete('/department/{department}', [DepartmentController::class, 'destroy'])->name('settings.departments.destroy');
