<?php

use App\Http\Controllers\Appointment\AppointmentController;
use App\Http\Controllers\Appointment\TreatmentController;
use App\Http\Controllers\Auth\StaffVerificationController;
use App\Http\Controllers\Billing\BillingController;
use App\Http\Controllers\HelperController;
use App\Http\Controllers\MedicineBillController;
use App\Http\Controllers\Patient\PatientListController;
use App\Http\Controllers\Patient\TodayController;
use App\Http\Controllers\Settings\ClinicBranchController;
use App\Http\Controllers\Settings\ComboOfferController;
use App\Http\Controllers\Settings\DepartmentController;
use App\Http\Controllers\Settings\DiseaseController;
use App\Http\Controllers\Settings\InsuranceController;
use App\Http\Controllers\Settings\MedicineController;
use App\Http\Controllers\Settings\MenuItemController;
use App\Http\Controllers\Settings\PermissionController;
use App\Http\Controllers\Settings\RoleController;
use App\Http\Controllers\Settings\TreatmentCostController;
use App\Http\Controllers\Settings\TreatmentPlanController;
use App\Http\Controllers\Settings\UserController;
use App\Http\Controllers\Staff\StaffListController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth.login');
})->middleware('guest');

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

Route::group(['middleware' => ['role:Superadmin|Admin']], function () {

    Route::resource('/permissions', PermissionController::class);
    Route::get('/permissions/{permissionId}/delete', [PermissionController::class, 'destroy']);

    //Route::resource('/roles', RoleController::class);
    Route::resource('roles', RoleController::class)->names([
        'index' => 'roles.index',
        'create' => 'roles.create',
        'store' => 'roles.store',
        'show' => 'roles.show',
        'edit' => 'roles.edit',
        'update' => 'roles.update',
        'destroy' => 'roles.destroy',
    ]);
    Route::get('/roles/{roleId}/delete', [RoleController::class, 'destroy']);
    Route::get('/roles/{roleId}/give-permissions', [RoleController::class, 'addPermissionToRole']);
    Route::put('/roles/{roleId}/give-permissions', [RoleController::class, 'givePermissionToRole']);
    Route::resource('users', UserController::class);
    Route::get('users/{userId}/delete', [UserController::class, 'destroy']);

});
Route::group(['middleware' => ['role:Superadmin']], function () {
    Route::resource('menu_items', MenuItemController::class, [
        'names' => [
            'index' => 'menu_items.index',
            'create' => 'menu_items.create',
            'store' => 'menu_items.store',
            'show' => 'menu_items.show',
            'edit' => 'menu_items.edit',
            'update' => 'menu_items.update',
            'destroy' => 'menu_items.destroy',
        ],
    ]);
});

Route::get('/get-states/{countryId}', [HelperController::class, 'getStates'])->name('get.states');
Route::get('/get-cities/{stateId}', [HelperController::class, 'getCities'])->name('get.cities');
Route::get('/session-data', [HelperController::class, 'getSessionData']);
Route::get('/fetch-doctors/{branchId}', [PatientListController::class, 'fetchDoctors'])->name('get.doctors');
Route::get('/fetch-existingAppoinmtents/{branchId}', [PatientListController::class, 'fetchExistingAppointments'])->name('get.exisitingAppointments');
Route::get('/fetch-ExistingExamination/{toothId}/{appId}/{patientId}', [TreatmentController::class, 'fetchExistingExamination'])->name('get.toothExamination');
Route::post('/generate-pdf', [HelperController::class, 'generateTreatmentPdf'])->name('generate.pdf');
Route::get('/fetch-teeth-details/{patientId}/{appId}', [HelperController::class, 'fetchTeethDetails'])->name('fetch.teeth.details');
Route::post('/download-prescription', [HelperController::class, 'generatePrescriptionPdf'])->name('download.prescription');
Route::get('/print-prescription', [HelperController::class, 'printPrescription'])->name('print.prescription');

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

Route::get('/insurance', [InsuranceController::class, 'index'])->name('settings.insurance');
Route::post('/insurance/store', [InsuranceController::class, 'store'])->name('settings.insurance.store');
Route::get('/insurance/{insurance}/edit', [InsuranceController::class, 'edit'])->name('settings.insurance.edit');
Route::post('/insurance/update', [InsuranceController::class, 'update'])->name('settings.insurance.update');
Route::delete('/insurance/{insurance}', [InsuranceController::class, 'destroy'])->name('settings.insurance.destroy');

Route::get('/treatment_plan', [TreatmentPlanController::class, 'index'])->name('settings.treatment_plan');
Route::post('/treatment_plan/store', [TreatmentPlanController::class, 'store'])->name('settings.treatment_plan.store');
Route::get('/treatment_plan/{treatment_plan}/edit', [TreatmentPlanController::class, 'edit'])->name('settings.treatment_plan.edit');
Route::post('/treatment_plan/update', [TreatmentPlanController::class, 'update'])->name('settings.treatment_plan.update');
Route::delete('/treatment_plan/{treatment_plan}', [TreatmentPlanController::class, 'destroy'])->name('settings.treatment_plan.destroy');

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
Route::delete('/patient_list/{patientId}', [PatientListController::class, 'destroy'])->name('patient.patient_list.destroy');
Route::post('/patient_list/{patientId}', [PatientListController::class, 'changeStatus'])->name('patient.patient_list.changeStatus');
Route::post('/patient_list/appointment/store', [PatientListController::class, 'appointmentBooking'])->name('patient.patient_list.booking');
Route::get('/patient_list/{patientId}/appointment', [PatientListController::class, 'appointmentDetails'])->name('patient.patient_list.appointment');
Route::get('/patient_list/{patientId}/view', [PatientListController::class, 'show'])->name('patient.patient_list.view');

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

Route::get('/appointment', [AppointmentController::class, 'index'])->name('appointment');
Route::get('/appointment/add', [AppointmentController::class, 'create'])->name('appointment.create');
Route::post('/appointment/store', [AppointmentController::class, 'store'])->name('appointment.store');
Route::get('/appointment/{appointment}/edit', [AppointmentController::class, 'edit'])->name('appointment.edit');
Route::post('/appointment/update', [AppointmentController::class, 'update'])->name('appointment.update');
Route::post('/appointment/{appointment}', [AppointmentController::class, 'destroy'])->name('appointment.destroy');

Route::get('/combo_offer', [ComboOfferController::class, 'index'])->name('settings.combo_offer');
Route::post('/combo_offer/store', [ComboOfferController::class, 'store'])->name('settings.combo_offer.store');
Route::get('/combo_offer/{offer}/edit', [ComboOfferController::class, 'edit'])->name('settings.combo_offer.edit');
Route::post('/combo_offer/{offer}/update', [ComboOfferController::class, 'update'])->name('settings.combo_offer.update');
Route::delete('/combo_offer/{offer}', [ComboOfferController::class, 'destroy'])->name('settings.combo_offer.destroy');

Route::get('/appointment/{appointment}/treatment', [TreatmentController::class, 'index'])->name('treatment');
Route::post('/treatment/store', [TreatmentController::class, 'store'])->name('treatment.store');

Route::get('/images/{dataId}', [TreatmentController::class, 'getImages'])->name('images.index');
Route::delete('/delete-image', [TreatmentController::class, 'deleteImage'])->name('delete.image');

Route::get('/appointment/fetchtreatment/{appointment}', [TreatmentController::class, 'show'])->name('treatment.show');
Route::get('/appointment/fetchTreatmentCharge/{appointment}', [TreatmentController::class, 'showCharge'])->name('treatment.showCharge');
Route::delete('/treatment/{toothExamId}', [TreatmentController::class, 'destroy'])->name('treatment.destroy');
Route::post('/treatment/details/store', [TreatmentController::class, 'storeDetails'])->name('treatment.details.store');

Route::get('/billing', [BillingController::class, 'index'])->name('billing');
Route::get('/billing/add/{appointmentId}', [BillingController::class, 'create'])->name('billing.create');
Route::post('/billing/combo/{appointmentId}', [BillingController::class, 'comboOffer'])->name('billing.combo');
Route::post('/billing/store', [BillingController::class, 'store'])->name('billing.store');
Route::post('/billing/payment', [BillingController::class, 'payment'])->name('billing.payment');

Route::get('/billing/{billing}/edit', [BillingController::class, 'edit'])->name('billing.edit');
Route::post('/billing/update', [BillingController::class, 'update'])->name('billing.update');
Route::post('/billing/{billing}', [BillingController::class, 'destroy'])->name('billing.destroy');

Route::get('/medicineBilling', [MedicineBillController::class, 'index'])->name('medicineBilling');
Route::get('/medicineBilling/add/{appointmentId}', [MedicineBillController::class, 'create'])->name('medicineBilling.create');
Route::post('/medicineBilling/combo/{appointmentId}', [MedicineBillController::class, 'comboOffer'])->name('medicineBilling.combo');
Route::post('/medicineBilling/store', [MedicineBillController::class, 'store'])->name('medicineBilling.store');
Route::post('/medicineBilling/payment', [MedicineBillController::class, 'payment'])->name('medicineBilling.payment');
Route::get('/medicineBilling/{billing}/edit', [MedicineBillController::class, 'edit'])->name('medicineBilling.edit');
Route::post('/medicineBilling/update', [MedicineBillController::class, 'update'])->name('medicineBilling.update');
Route::post('/medicineBilling/{billing}', [MedicineBillController::class, 'destroy'])->name('medicineBilling.destroy');
