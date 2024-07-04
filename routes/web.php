<?php

use App\Http\Controllers\Auth\StaffVerificationController;
use App\Http\Controllers\Auth\VerificationController;
use App\Http\Controllers\HelperController;
use App\Http\Controllers\Patient\PatientListController;
use App\Http\Controllers\Patient\TodayController;
use App\Http\Controllers\Settings\ClinicBranchController;
use App\Http\Controllers\Settings\DepartmentController;
use App\Http\Controllers\Settings\DiseaseController;
use App\Http\Controllers\Settings\MedicineController;
use App\Http\Controllers\Settings\TreatmentCostController;
use App\Http\Controllers\Staff\StaffListController;
use App\Http\Controllers\Staff\DoctorListController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth.login');
});

Route::get('/formclinic', function () {
    return view('forms.clinic_form');
});

Auth::routes();

// Example for email verification route
Auth::routes(['verify' => true]);

// Route::get('/password/reset/{token}', function () {
//     return view('auth.passwords.reset');
// });


Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/get-states/{countryId}', [HelperController::class, 'getStates'])->name('get.states');
Route::get('/get-cities/{stateId}', [HelperController::class, 'getCities'])->name('get.cities');
Route::get('/fetch-doctors/{branchId}', [PatientListController::class, 'fetchDoctors'])->name('get.doctors');

Route::get('/clinic', [ClinicBranchController::class, 'index'])->name('settings.clinic');
Route::post('/clinic/create', [ClinicBranchController::class, 'create'])->name('settings.clinic.create');
Route::post('/clinic/store', [ClinicBranchController::class, 'store'])->name('settings.clinic.store');
Route::get('/clinic/{clinic}/edit', [ClinicBranchController::class, 'edit'])->name('settings.clinic.edit');
Route::post('/clinic/update', [ClinicBranchController::class, 'update'])->name('settings.clinic.update');
Route::post('/clinic/{clinic}/{status}', [ClinicBranchController::class, 'statusChange'])->name('settings.clinic.destroy');

Route::get('/disease', [DiseaseController::class, 'index'])->name('settings.disease');
Route::post('/disease/create', [DiseaseController::class, 'create'])->name('settings.disease.create');
Route::post('/disease/store', [DiseaseController::class, 'store'])->name('settings.disease.store');
Route::get('/disease/{disease}/edit', [DiseaseController::class, 'edit'])->name('settings.disease.edit');
Route::post('/disease/update', [DiseaseController::class, 'update'])->name('settings.disease.update');
Route::delete('/disease/{disease}', [DiseaseController::class, 'destroy'])->name('settings.disease.destroy');

Route::group(['middleware' => ['permission:departments']], function () {
    Route::get('/department', [DepartmentController::class, 'index'])->name('settings.department');
    Route::post('/department/store', [DepartmentController::class, 'store'])->name('settings.department.store');
    Route::get('/department/{department}/edit', [DepartmentController::class, 'edit'])->name('settings.department.edit');
    Route::post('/department/update', [DepartmentController::class, 'update'])->name('settings.department.update');
    Route::delete('/department/{department}', [DepartmentController::class, 'destroy'])->name('settings.departments.destroy');
});
Route::get('/treatment_cost', [TreatmentCostController::class, 'index'])->name('settings.treatment_cost');
Route::post('/treatment_cost/store', [TreatmentCostController::class, 'store'])->name('settings.treatment_cost.store');
Route::get('/treatment_cost/{department}/edit', [TreatmentCostController::class, 'edit'])->name('settings.treatment_cost.edit');
Route::post('/treatment_cost/{treatment_cost}/update', [TreatmentCostController::class, 'update'])->name('settings.treatment_cost.update');
Route::delete('/treatment_cost/{treatment_cost}', [TreatmentCostController::class, 'destroy'])->name('settings.treatment_cost.destroy');

Route::get('/medicine', [MedicineController::class, 'index'])->name('settings.medicine');
Route::post('/medicine/store', [MedicineController::class, 'store'])->name('settings.medicine.store');
Route::get('/medicine/{medicine}/edit', [MedicineController::class, 'edit'])->name('settings.medicine.edit');
Route::post('/medicine/{medicine}/update', [MedicineController::class, 'update'])->name('settings.medicine.update');
Route::delete('/medicine/{medicine}', [MedicineController::class, 'destroy'])->name('settings.medicine.destroy');

Route::get('/patient_list', [PatientListController::class, 'index'])->name('patient.patient_list');
Route::get('/patient_list/add', [PatientListController::class, 'create'])->name('patient.patient_list.create');
Route::post('/patient_list/store', [PatientListController::class, 'store'])->name('patient.patient_list.store');
Route::get('/patient_list/{patient_list}/edit', [PatientListController::class, 'edit'])->name('patient.patient_list.edit');
Route::post('/patient_list/update', [PatientListController::class, 'update'])->name('patient.patient_list.update');
Route::delete('/patient_list/{patient_list}', [PatientListController::class, 'destroy'])->name('patient.patient_list.destroy');

Route::get('/today', [TodayController::class, 'index'])->name('patient.today');
Route::post('/today/store', [TodayController::class, 'store'])->name('patient.today.store');
Route::get('/today/{today}/edit', [TodayController::class, 'edit'])->name('patient.today.edit');
Route::post('/today/update', [TodayController::class, 'update'])->name('patient.today.update');
Route::delete('/today/{today}', [TodayController::class, 'destroy'])->name('patient.today.destroy');

Route::get('/totalpatients', [TodayController::class, 'getTotal'])->name('totalpatients');

Route::get('/staff_list', [StaffListController::class, 'index'])->name('staff.staff_list');
Route::get('/staff_list/add', [StaffListController::class, 'create'])->name('staff.staff_list.create');
Route::post('/staff_list/store', [StaffListController::class, 'store'])->name('staff.staff_list.store');
Route::get('/staff_list/{staff_list}/edit', [StaffListController::class, 'edit'])->name('staff.staff_list.edit');
Route::post('/staff_list/{staffId}', [StaffListController::class, 'changeStatus'])->name('staff.staff_list.changeStatus');
Route::get('/staff_list/{staff_list}/view', [StaffListController::class, 'view'])->name('staff.staff_list.view');
Route::post('/staff_list/update', [StaffListController::class, 'update'])->name('staff.staff_list.update');
Route::delete('/staff_list/{staffId}', [StaffListController::class, 'destroy'])->name('staff.staff_list.destroy');

Route::get('account/verify/{token}', [StaffVerificationController::class, 'verifyAccount'])->name('user.verify');
