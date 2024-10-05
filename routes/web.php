<?php

use App\Http\Controllers\Appointment\AppointmentController;
use App\Http\Controllers\Appointment\TreatmentController;
use App\Http\Controllers\AuditController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\ResetProfilePasswordController;
use App\Http\Controllers\Auth\StaffVerificationController;
use App\Http\Controllers\BackupController;
use App\Http\Controllers\Billing\BillingController;
use App\Http\Controllers\Billing\PaymentController;
use App\Http\Controllers\Expenses\ExpenseCategoryController;
use App\Http\Controllers\Expenses\ExpenseController;
use App\Http\Controllers\HelperController;
use App\Http\Controllers\LabBillController;
use App\Http\Controllers\MedicineBillController;
use App\Http\Controllers\Patient\PatientListController;
use App\Http\Controllers\Patient\TodayController;
use App\Http\Controllers\Payroll\AttendanceController;
use App\Http\Controllers\Payroll\EmployeeSalaryController;
use App\Http\Controllers\Payroll\EmployeeTypeController;
use App\Http\Controllers\Payroll\HolidayController;
use App\Http\Controllers\Payroll\PayHeadController;
use App\Http\Controllers\Payroll\WorkController;
use App\Http\Controllers\PlaceOrderController;
use App\Http\Controllers\Purchases\PurchaseController;
use App\Http\Controllers\Purchases\SupplierController;
use App\Http\Controllers\Purchases\MedicinePurchaseController;
use App\Http\Controllers\Report\ReportController;
use App\Http\Controllers\Settings\ClinicBranchController;
use App\Http\Controllers\Settings\ComboOfferController;
use App\Http\Controllers\Settings\DepartmentController;
use App\Http\Controllers\Settings\DiseaseController;
use App\Http\Controllers\Settings\InsuranceController;
use App\Http\Controllers\Settings\MedicineController;
use App\Http\Controllers\Settings\LeaveController;
use App\Http\Controllers\Settings\LeaveTypeController;
use App\Http\Controllers\Settings\MenuItemController;
use App\Http\Controllers\Settings\PermissionController;
use App\Http\Controllers\Settings\RoleController;
use App\Http\Controllers\Settings\TreatmentCostController;
use App\Http\Controllers\Settings\TreatmentPlanController;
use App\Http\Controllers\Settings\UserController;
use App\Http\Controllers\SmsController;
use App\Http\Controllers\Staff\StaffListController;
use App\Http\Controllers\TechnicianController;
use App\Http\Controllers\TechnicianCostController;
use App\Http\Controllers\UpdateOrderController;
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
Route::middleware(['auth', 'check.session'])->group(function () {
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::get('/home/{usertype}', [App\Http\Controllers\HomeController::class, 'index'])->name('dashboard.userType');

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
    Route::post('/resetPassword', [ResetProfilePasswordController::class, 'resetProfilePassword'])->name('reset.password');
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
    Route::post('/download-patientidcard', [HelperController::class, 'generatePatientIDCardPdf'])->name('download.patientidcard');

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

    Route::group(['middleware' => ['permission:settings departments']], function () {
        Route::get('/department', [DepartmentController::class, 'index'])->name('settings.department');
        Route::post('/department/store', [DepartmentController::class, 'store'])->name('settings.department.store');
        Route::get('/department/{department}/edit', [DepartmentController::class, 'edit'])->name('settings.department.edit');
        Route::post('/department/update', [DepartmentController::class, 'update'])->name('settings.department.update');
        Route::delete('/department/{department}', [DepartmentController::class, 'destroy'])->name('settings.departments.destroy');
    });

    Route::get('/leaveType', [LeaveTypeController::class, 'index'])->name('settings.leaveType');
    Route::post('/leaveType/store', [LeaveTypeController::class, 'store'])->name('settings.leaveType.store');
    Route::get('/leaveType/{leaveType}/edit', [LeaveTypeController::class, 'edit'])->name('settings.leaveType.edit');
    Route::post('/leaveType/update', [LeaveTypeController::class, 'update'])->name('settings.leaveType.update');
    Route::delete('/leaveType/{leaveType}', [LeaveTypeController::class, 'destroy'])->name('settings.leaveType.destroy');

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
    Route::get('/patient_list/{patientId}/bill/', [PatientListController::class, 'bill'])->name('patient.patient_list.allbill');


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
    // Route::get('/staff_list/{staff_list}/view', [StaffListController::class, 'view'])->name('staff.staff_list.view');
    Route::get('/staff_list/view', [StaffListController::class, 'view'])->name('staff.staff_list.view');
    Route::post('/staff_list/update', [StaffListController::class, 'update'])->name('staff.staff_list.update');
    Route::delete('/staff_list/{staffId}', [StaffListController::class, 'destroy'])->name('staff.staff_list.destroy');

    Route::get('account/verify/{token}', [StaffVerificationController::class, 'verifyAccount'])->name('user.verify');

    Route::get('/appointment', [AppointmentController::class, 'index'])->name('appointment');
    Route::get('/appointment/add', [AppointmentController::class, 'create'])->name('appointment.create');
    Route::post('/appointment/store', [AppointmentController::class, 'store'])->name('appointment.store');
    Route::get('/appointment/{appointment}/edit', [AppointmentController::class, 'edit'])->name('appointment.edit');
    Route::post('/appointment/update', [AppointmentController::class, 'update'])->name('appointment.update');
    Route::post('/appointment/{appointment}', [AppointmentController::class, 'destroy'])->name('appointment.destroy');
    Route::get('/appointment/changeStatus/{appointment}', [AppointmentController::class, 'changeStatus'])->name('appointment.changeStatus');
    Route::get('/appointment/getbranchDoctors/{branchId}', [AppointmentController::class, 'showForm'])->name('appointment.getBranchDoctors');


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
    Route::get('/billing/add', [BillingController::class, 'add'])->name('billing.add');
    Route::post('/billing/combo/{appointmentId}', [BillingController::class, 'comboOffer'])->name('billing.combo');
    Route::post('/billing/store', [BillingController::class, 'store'])->name('billing.store');
    Route::post('/billing/payment', [BillingController::class, 'payment'])->name('billing.payment');
    Route::post('/billing/paymentReceipt', [BillingController::class, 'paymentReceipt'])->name('billing.paymentReceipt');
    Route::get('/print-receipt/{fileName}', [HelperController::class, 'printReceipt'])->name('print.receipt');
    Route::get('/billing/{billing}/edit', [BillingController::class, 'edit'])->name('billing.edit');
    Route::post('/billing/update', [BillingController::class, 'update'])->name('billing.update');
    Route::post('/billing/{billing}', [BillingController::class, 'destroy'])->name('billing.destroy');

    Route::get('/medicineBilling', [MedicineBillController::class, 'index'])->name('medicineBilling');
    Route::get('/medicineBilling/add/{appointmentId}', [MedicineBillController::class, 'create'])->name('medicineBilling.create');
    Route::post('/medicineBilling/store', [MedicineBillController::class, 'store'])->name('medicineBilling.store');
    Route::post('/medicineBilling/paymentReceipt', [MedicineBillController::class, 'paymentReceipt'])->name('medicineBilling.paymentReceipt');
    Route::post('/medicineBilling/combo/{appointmentId}', [MedicineBillController::class, 'comboOffer'])->name('medicineBilling.combo');
    Route::post('/medicineBilling/payment', [MedicineBillController::class, 'payment'])->name('medicineBilling.payment');
    Route::get('/medicineBilling/{billing}/edit', [MedicineBillController::class, 'edit'])->name('medicineBilling.edit');
    Route::post('/medicineBilling/update', [MedicineBillController::class, 'update'])->name('medicineBilling.update');
    Route::post('/medicineBilling/{billing}', [MedicineBillController::class, 'destroy'])->name('medicineBilling.destroy');

    Route::get('/duepayment', [PaymentController::class, 'index'])->name('duePayment');
    Route::get('/search-patient', [PaymentController::class, 'searchPatient'])->name('duePayment.searchPatient');
    Route::post('/pay-due', [PaymentController::class, 'payDue'])->name('duePayment.due');
    Route::post('/duepayment/paymentReceipt', [PaymentController::class, 'paymentReceipt'])->name('duePayment.paymentReceipt');

    Route::get('/report', [ReportController::class, 'index'])->name('report');
    Route::post('/report/collection', [ReportController::class, 'collection'])->name('report.collection');
    Route::post('/report/income', [ReportController::class, 'income'])->name('report.income');
    Route::post('/report/service', [ReportController::class, 'service'])->name('report.service');
    Route::post('/report/patient', [ReportController::class, 'patient'])->name('report.patient');
    Route::post('/report/disease', [ReportController::class, 'disease'])->name('report.disease');
    Route::post('/report/audit_cancell', [AuditController::class, 'auditCancell'])->name('report.audit_cancell');
    Route::post('/report/audit_patient', [ReportController::class, 'auditPatient'])->name('report.auditPatient');
    Route::post('/report/audit_bill', [ReportController::class, 'auditBill'])->name('report.auditBill');
    Route::post('/report/consolidated', [ReportController::class, 'consolidatedReport'])->name('report.consolidated');
    Route::get('/report/expensesdatewise', [ExpenseController::class, 'getExpensesByDate'])->name('expenses.by.date');
    Route::get('/report/attendance/month', [AttendanceController::class, 'getMonthwiseAttendance'])->name('report.attendance.month');

    Route::get('/appointments-by-hour', [App\Http\Controllers\HomeController::class, 'getAppointmentsByHour'])->name('appointments-by-hour');
    Route::get('/appointments-by-month', [App\Http\Controllers\HomeController::class, 'getAppointmentsByMonth'])->name('appointments-by-month');


    Route::get('/leave', [LeaveController::class, 'index'])->name('leave');
    Route::post('/leave/store', [LeaveController::class, 'store'])->name('leave.store');
    Route::get('/leave/{leave}/edit', [LeaveController::class, 'edit'])->name('leave.edit');
    Route::post('/leave/update', [LeaveController::class, 'update'])->name('leave.update');
    Route::delete('/leave/{leave}', [LeaveController::class, 'destroy'])->name('leave.destroy');
    Route::get('/leave/approve/{leave}', [LeaveController::class, 'approveLeave'])->name('leave.approve');
    Route::post('/leave/reject/{leave}', [LeaveController::class, 'rejectLeave'])->name('leave.reject');

    Route::get('/technicians', [TechnicianController::class, 'index'])->name('technicians');
    Route::post('/technicians/store', [TechnicianController::class, 'store'])->name('technicians.store');
    Route::get('/technicians/{technician}/edit', [TechnicianController::class, 'edit'])->name('technicians.edit');
    Route::post('/technicians/update', [TechnicianController::class, 'update'])->name('technicians.update');
    Route::delete('/technicians/{technician}', [TechnicianController::class, 'destroy'])->name('technicians.destroy');

    Route::get('/technicianCost', [TechnicianCostController::class, 'index'])->name('technicianCost');
    Route::post('/technicianCost/store', [TechnicianCostController::class, 'store'])->name('technicianCost.store');


    Route::get('/place_order', [PlaceOrderController::class, 'index'])->name('order.place_order');
    Route::post('/place_order/create', [PlaceOrderController::class, 'create'])->name('orders.create');
    Route::post('/place_order/store', [PlaceOrderController::class, 'store'])->name('orders.store');

    Route::get('/track_order', [UpdateOrderController::class, 'index'])->name('order.track_order');
    Route::post('/track_order/{orderId}', [UpdateOrderController::class, 'destroy'])->name('order.destroy');
    Route::post('/track_order/delivered/{orderId}', [UpdateOrderController::class, 'update'])->name('order.update');
    Route::get('/track_order/{orderId}/edit', [UpdateOrderController::class, 'edit'])->name('order.edit');
    Route::post('/track_order/repeat/{orderId}', [UpdateOrderController::class, 'repeat'])->name('order.repeat');

    Route::get('/labPayment', [LabBillController::class, 'index'])->name('labPayment');
    Route::post('/labPayment/create', [LabBillController::class, 'create'])->name('labPayment.create');
    Route::post('/labPayment/store', [LabBillController::class, 'store'])->name('labPayment.store');
    Route::get('/labPayment/show', [LabBillController::class, 'show'])->name(name: 'labPayment.show');
    Route::post('/labPayment/cancel/{billId}', [LabBillController::class, 'destroy'])->name(name: 'labPayment.destroy');

    Route::get('/db_backup', [BackupController::class, 'index'])->name('settings.db_backup');
    Route::post('send-sms', [SmsController::class, 'sendSms'])->name('send.sms');

    Route::get('/expenseCategory', [ExpenseCategoryController::class, 'index'])->name('expenseCategory');
    Route::post('/expenseCategory/store', [ExpenseCategoryController::class, 'store'])->name('expenseCategory.store');
    Route::get('/expenseCategory/{categoryId}/edit', [ExpenseCategoryController::class, 'edit'])->name('expenseCategory.edit');
    Route::post('/expenseCategory/update', [ExpenseCategoryController::class, 'update'])->name('expenseCategory.update');
    Route::delete('/expenseCategory/{categoryId}', [ExpenseCategoryController::class, 'destroy'])->name('expenseCategory.destroy');
    Route::get('/clinicExpense', [ExpenseController::class, 'index'])->name('clinicExpense');
    Route::post('/clinicExpense/store', [ExpenseController::class, 'store'])->name('expense.expense.store');
    Route::get('/clinicExpense/{expense}/edit', [ExpenseController::class, 'edit'])->name('expense.expense.edit');
    Route::post('/clinicExpense/update', [ExpenseController::class, 'update'])->name('expense.expense.update');
    Route::delete('/clinicExpense/{expense}', [ExpenseController::class, 'destroy'])->name('expense.expense.destroy');
    Route::get('clinicExpense/{id}/download-bills', [ExpenseController::class, 'downloadBills']);

    Route::get('/suppliers', [SupplierController::class, 'index'])->name('suppliers');
    Route::post('/suppliers/store', [SupplierController::class, 'store'])->name('suppliers.store');
    Route::get('/suppliers/{supplier}/edit', [SupplierController::class, 'edit'])->name('suppliers.edit');
    Route::post('/suppliers/update', [SupplierController::class, 'update'])->name('suppliers.update');
    Route::delete('/suppliers/{supplier}', [SupplierController::class, 'destroy'])->name('suppliers.destroy');

    Route::get('/purchases', [PurchaseController::class, 'index'])->name('purchases');
    Route::get('/purchases/get', [PurchaseController::class, 'get'])->name(name: 'purchases.get');
    Route::get('/getSupplierDetails/{id}', [PurchaseController::class, 'getSupplierDetails'])->name('purchase.getSupplierDetails');
    Route::post('/purchases/store', [PurchaseController::class, 'store'])->name('purchases.store');
    Route::get('/medicine/purchases', [MedicinePurchaseController::class, 'index'])->name('medicine.purchases');
    Route::post('/medicine/purchases/store', [MedicinePurchaseController::class, 'store'])->name('medicine.purchases.store');
    Route::get('/medicine/purchases/all', [MedicinePurchaseController::class, 'purchaseHistory'])->name(name: 'medicine.purchases.history');
    Route::get('/medicine/purchases/view/{id}', [MedicinePurchaseController::class, 'show'])->name('medicine.purchase.view');
    Route::post('/medicine/purchases/cancel/{id}', [MedicinePurchaseController::class, 'destroy'])->name(name: 'medicine.purchase.destroy');
    Route::get('/medicine/purchases/edit/{id}', [MedicinePurchaseController::class, 'edit'])->name('medicine.purchase.edit');
    Route::post('/medicine_purchases/update', [MedicinePurchaseController::class, 'update'])->name('medicine.purchases.update');
    Route::get('purchases/{id}/download-bills', [PurchaseController::class, 'downloadBills']);
    Route::get('/purchases/view/{id}', [PurchaseController::class, 'show'])->name('purchase.view');
    Route::get('/purchases/edit/{id}', [PurchaseController::class, 'edit'])->name('purchase.edit');
    Route::post('/purchases/update', [PurchaseController::class, 'update'])->name('purchases.update');
    Route::post('/purchases/cancel/{id}', [PurchaseController::class, 'destroy'])->name(name: 'purchase.destroy');


    Route::get('/holidays', [HolidayController::class, 'index'])->name('holidays');
    Route::post('/holidays/store', [HolidayController::class, 'store'])->name('holidays.store');
    Route::get('/holidays/{holidayId}/edit', [HolidayController::class, 'edit'])->name('holidays.edit');
    Route::post('/holidays/update', [HolidayController::class, 'update'])->name('holidays.update');
    Route::delete('/holidays/delete/{holidayId}', [HolidayController::class, 'destroy'])->name('holidays.destroy');

    Route::get('/pay_heads', [PayHeadController::class, 'index'])->name('payHeads');
    Route::post('/pay_heads/store', [PayHeadController::class, 'store'])->name('payHeads.store');
    Route::get('/pay_heads/{payheadId}/edit', [PayHeadController::class, 'edit'])->name('payHeads.edit');
    Route::post('/pay_heads/update', [PayHeadController::class, 'update'])->name('payHeads.update');

    Route::get('/employee_types', [EmployeeTypeController::class, 'index'])->name('employeeTypes');
    Route::post('/employee_types/store', [EmployeeTypeController::class, 'store'])->name('employeeTypes.store');
    Route::get('/employee_types/{employeeTypeId}/edit', [EmployeeTypeController::class, 'edit'])->name('employeeTypes.edit');
    Route::post('/employee_types/update', [EmployeeTypeController::class, 'update'])->name('employeeTypes.update');

    Route::get('/attendance', [AttendanceController::class, 'index'])->name('attendance');
    Route::post('/attendance/create', [AttendanceController::class, 'create'])->name('attendance.create');
    Route::post('/attendance/store', [AttendanceController::class, 'store'])->name('attendance.store');


    Route::get('/employee_salary', [EmployeeSalaryController::class, 'index'])->name('employeeSalary');
    Route::get('/employee_salary/create/{id}', [EmployeeSalaryController::class, 'create'])->name('salary.create');
    Route::get('/employee_salary/view/{id}', [EmployeeSalaryController::class, 'show'])->name('salary.view');
    Route::get('/employee_salary/edit/{id}', [EmployeeSalaryController::class, 'edit'])->name('salary.edit');
    Route::post('/employee_salary/update', [EmployeeSalaryController::class, 'update'])->name('salary.update');
    Route::post('/employee_salary/cancel/{id}', [EmployeeSalaryController::class, 'destroy'])->name(name: 'salary.destroy');
    Route::post('/employee_salary/store', [EmployeeSalaryController::class, 'store'])->name('salary.store');
    Route::post('/download-salaryslip', [EmployeeSalaryController::class, 'generatesalaryslipPdf'])->name('download.salaryslip');


    Route::post('/logout-cancel', function () {
        Auth::logout();
        return response()->json(['success' => true]);
    });

    Route::post('/start-work', action: [WorkController::class, 'store'])->name('attendance.start');
    Route::post('/finish-work', [WorkController::class, 'update'])->name('attendance.finish');
});