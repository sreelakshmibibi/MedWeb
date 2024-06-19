<?php

use App\Http\Controllers\Settings\ClinicBranchController;
use App\Http\Controllers\Settings\DepartmentController;
use App\Http\Controllers\Settings\TreatmentCostController;
use App\Http\Controllers\Settings\MedicineController;
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
Route::post('/clinic/store', [ClinicBranchController::class, 'store'])->name('settings.clinic.store');
Route::get('/clinic/{clinic}/edit', [ClinicBranchController::class, 'edit'])->name('settings.clinic.edit');
Route::post('/clinic/update', [ClinicBranchController::class, 'update'])->name('settings.clinic.update');
Route::get('/clinic/{clinic}', [ClinicBranchController::class, 'destroy'])->name('settings.clinic.destroy');

Route::get('/department', [DepartmentController::class, 'index'])->name('settings.department');
Route::post('/department/store', [DepartmentController::class, 'store'])->name('settings.department.store');
Route::get('/department/{department}/edit', [DepartmentController::class, 'edit'])->name('settings.department.edit');
Route::post('/department/update', [DepartmentController::class, 'update'])->name('settings.department.update');
Route::delete('/department/{department}', [DepartmentController::class, 'destroy'])->name('settings.departments.destroy');

Route::get('/treatment_cost', [TreatmentCostController::class, 'index'])->name('settings.treatment_cost');
Route::post('/treatment_cost/store', [TreatmentCostController::class, 'store'])->name('settings.treatment_cost.store');
Route::get('/treatment_cost/{department}/edit', [TreatmentCostController::class, 'edit'])->name('settings.treatment_cost.edit');
Route::post('/treatment_cost/update', [TreatmentCostController::class, 'update'])->name('settings.treatment_cost.update');
Route::delete('/treatment_cost/{treatment_cost}', [TreatmentCostController::class, 'destroy'])->name('settings.treatment_cost.destroy');

Route::get('/medicine', [MedicineController::class, 'index'])->name('settings.medicine');
Route::post('/medicine/store', [MedicineController::class, 'store'])->name('settings.medicine.store');
Route::get('/medicine/{department}/edit', [MedicineController::class, 'edit'])->name('settings.medicine.edit');
Route::post('/medicine/update', [MedicineController::class, 'update'])->name('settings.medicine.update');
Route::delete('/medicine/{medicine}', [MedicineController::class, 'destroy'])->name('settings.medicine.destroy');
