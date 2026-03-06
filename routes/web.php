<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DentalChartController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\FinancialController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\SuperAdmin\ClinicController as SuperAdminClinicController;
use App\Http\Controllers\SuperAdmin\DashboardController as SuperAdminDashboard;
use App\Http\Controllers\SuperAdmin\SubscriptionController;
use App\Http\Controllers\SuperAdmin\SubscriptionPlanController;
use App\Http\Controllers\SuperAdmin\PlatformAccountingController;
use App\Http\Controllers\Clinic\PrescriptionController;
use App\Http\Controllers\Clinic\TreatmentController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\UserManagementController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\TreatmentPlanController;
use Illuminate\Support\Facades\Route;

// ─── Public Routes ────────────────────────────────────────────────────────────

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/lang/{locale}', function ($locale) {
    if (in_array($locale, ['en', 'ar'])) {
        session(['locale' => $locale]);
    }
    return redirect()->back();
})->name('lang.switch');

// ─── Auth Routes ──────────────────────────────────────────────────────────────

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.post');
});

Route::post('/logout', [LoginController::class, 'logout'])
     ->middleware('auth')
     ->name('logout');

// ─── Super Admin Routes ───────────────────────────────────────────────────────

Route::middleware(['auth', 'role:superadmin'])
     ->prefix('superadmin')
     ->name('superadmin.')
     ->group(function () {

    Route::get('/dashboard', [SuperAdminDashboard::class, 'index'])->name('dashboard');

    // Clinic management
    Route::resource('clinics', SuperAdminClinicController::class);

    // Subscription plans
    Route::resource('plans', SubscriptionPlanController::class);

    // Clinic subscription management
    Route::get('clinics/{clinic}/subscriptions/assign', [SubscriptionController::class, 'assign'])->name('subscriptions.assign');
    Route::post('clinics/{clinic}/subscriptions', [SubscriptionController::class, 'store'])->name('subscriptions.store');
    Route::get('clinics/{clinic}/subscriptions/renew', [SubscriptionController::class, 'renew'])->name('subscriptions.renew');
    Route::post('clinics/{clinic}/subscriptions/renew', [SubscriptionController::class, 'processRenewal'])->name('subscriptions.process-renewal');
    Route::post('clinics/{clinic}/subscriptions/suspend', [SubscriptionController::class, 'suspend'])->name('subscriptions.suspend');
    Route::get('subscriptions', [SubscriptionController::class, 'index'])->name('subscriptions.index');

    // Platform accounting
    Route::get('accounting', [PlatformAccountingController::class, 'index'])->name('accounting.index');
    Route::get('accounting/income', [PlatformAccountingController::class, 'income'])->name('accounting.income');
    Route::get('accounting/expenses', [PlatformAccountingController::class, 'expenses'])->name('accounting.expenses');
    Route::post('accounting/expenses', [PlatformAccountingController::class, 'storeExpense'])->name('accounting.expenses.store');
    Route::get('accounting/reports', [PlatformAccountingController::class, 'reports'])->name('accounting.reports');
});

// ─── Clinic (Tenant) Routes ───────────────────────────────────────────────────

Route::middleware(['auth', 'clinic.access', 'subscription.active'])
     ->group(function () {

    // Dashboard (role-aware)
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Patients
    Route::get('/patients', [PatientController::class, 'index'])->name('patients.index');
    Route::get('/patients/create', [PatientController::class, 'create'])->name('patients.create');
    Route::post('/patients', [PatientController::class, 'store'])->name('patients.store');
    Route::get('/patients/{patient}', [PatientController::class, 'show'])->name('patients.show');
    Route::get('/patients/{patient}/edit', [PatientController::class, 'edit'])->name('patients.edit');
    Route::put('/patients/{patient}', [PatientController::class, 'update'])->name('patients.update');
    Route::delete('/patients/{patient}', [PatientController::class, 'destroy'])->name('patients.destroy');

    // Appointments / Visits
    Route::get('/appointments', [AppointmentController::class, 'index'])->name('appointments.index');
    Route::get('/appointments/create', [AppointmentController::class, 'create'])->name('appointments.create');
    Route::post('/appointments', [AppointmentController::class, 'store'])->name('appointments.store');
    Route::get('/visits/{visit}/edit', [AppointmentController::class, 'edit'])->name('appointments.edit');
    Route::put('/visits/{visit}', [AppointmentController::class, 'update'])->name('appointments.update');
    Route::patch('/visits/{visit}/status', [AppointmentController::class, 'updateStatus'])->name('visits.update-status');
    Route::delete('/visits/{visit}', [AppointmentController::class, 'destroy'])->name('appointments.destroy');

    // Prescriptions
    Route::get('/prescriptions', [PrescriptionController::class, 'index'])->name('prescriptions.index');
    Route::get('/patients/{patient}/prescriptions/create', [PrescriptionController::class, 'create'])->name('prescriptions.create');
    Route::post('/patients/{patient}/prescriptions', [PrescriptionController::class, 'store'])->name('prescriptions.store');
    Route::get('/prescriptions/{prescription}', [PrescriptionController::class, 'show'])->name('prescriptions.show');
    Route::get('/prescriptions/{prescription}/pdf', [PrescriptionController::class, 'pdf'])->name('prescriptions.pdf');
    Route::post('/prescriptions/{prescription}/whatsapp', [PrescriptionController::class, 'sendWhatsApp'])->name('prescriptions.whatsapp');

    // Treatment Records
    Route::get('/treatments', [TreatmentController::class, 'index'])->name('treatments.index');
    Route::get('/visits/{visit}/treatments/create', [TreatmentController::class, 'create'])->name('treatments.create');
    Route::post('/visits/{visit}/treatments', [TreatmentController::class, 'store'])->name('treatments.store');

    // Dental Chart
    Route::get('/patients/{patient}/dental-chart', [DentalChartController::class, 'show'])->name('dental-chart.show');
    Route::post('/patients/{patient}/dental-chart/send-report', [DentalChartController::class, 'finalizeAndSendReport'])->name('dental-chart.send-report');

    // Inventory
    Route::get('/inventory', [InventoryController::class, 'index'])->name('inventory.index');
    Route::get('/inventory/create', [InventoryController::class, 'create'])->name('inventory.create');
    Route::post('/inventory', [InventoryController::class, 'store'])->name('inventory.store');
    Route::get('/inventory/{product}', [InventoryController::class, 'show'])->name('inventory.show');
    Route::get('/inventory/{product}/edit', [InventoryController::class, 'edit'])->name('inventory.edit');
    Route::put('/inventory/{product}', [InventoryController::class, 'update'])->name('inventory.update');
    Route::delete('/inventory/{product}', [InventoryController::class, 'destroy'])->name('inventory.destroy');
    Route::post('/inventory/{product}/movements', [InventoryController::class, 'recordMovement'])->name('inventory.record-movement');

    // Suppliers
    Route::get('/suppliers', [SupplierController::class, 'index'])->name('suppliers.index');
    Route::get('/suppliers/create', [SupplierController::class, 'create'])->name('suppliers.create');
    Route::post('/suppliers', [SupplierController::class, 'store'])->name('suppliers.store');
    Route::get('/suppliers/{supplier}/edit', [SupplierController::class, 'edit'])->name('suppliers.edit');
    Route::put('/suppliers/{supplier}', [SupplierController::class, 'update'])->name('suppliers.update');
    Route::delete('/suppliers/{supplier}', [SupplierController::class, 'destroy'])->name('suppliers.destroy');

    // Treatment Plans
    Route::get('/patients/{patient}/treatment-plans/create', [TreatmentPlanController::class, 'create'])->name('treatment-plans.create');
    Route::post('/patients/{patient}/treatment-plans', [TreatmentPlanController::class, 'store'])->name('treatment-plans.store');
    Route::get('/patients/{patient}/treatment-plans/{plan}/edit', [TreatmentPlanController::class, 'edit'])->name('treatment-plans.edit');
    Route::put('/patients/{patient}/treatment-plans/{plan}', [TreatmentPlanController::class, 'update'])->name('treatment-plans.update');
    Route::delete('/patients/{patient}/treatment-plans/{plan}', [TreatmentPlanController::class, 'destroy'])->name('treatment-plans.destroy');

    // Financials
    Route::get('/financials', [FinancialController::class, 'index'])->name('financials.index');
    Route::get('/financials/reports', [FinancialController::class, 'reports'])->name('financials.reports');
    Route::post('/expenses', [FinancialController::class, 'storeExpense'])->name('expenses.store');

    // Invoices
    Route::get('/visits/{visit}/invoices/create', [InvoiceController::class, 'create'])->name('invoices.create');
    Route::post('/visits/{visit}/invoices', [InvoiceController::class, 'store'])->name('invoices.store');
    Route::get('/invoices', [InvoiceController::class, 'index'])->name('invoices.index');
    Route::get('/invoices/{invoice}', [InvoiceController::class, 'show'])->name('invoices.show');
    Route::get('/invoices/{invoice}/pdf', [InvoiceController::class, 'pdf'])->name('invoices.pdf');
    Route::post('/invoices/{invoice}/payments', [PaymentController::class, 'store'])->name('payments.store');

    // Settings (clinic admin only)
    Route::middleware('role:clinic_admin,superadmin')->group(function () {
        Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
        Route::post('/settings', [SettingsController::class, 'update'])->name('settings.update');
    });

    // User Management (clinic admin only)
    Route::middleware('role:clinic_admin,superadmin')->group(function () {
        Route::get('/users', [UserManagementController::class, 'index'])->name('users.index');
        Route::get('/users/create', [UserManagementController::class, 'create'])->name('users.create');
        Route::post('/users', [UserManagementController::class, 'store'])->name('users.store');
        Route::get('/users/{user}/edit', [UserManagementController::class, 'edit'])->name('users.edit');
        Route::put('/users/{user}', [UserManagementController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}', [UserManagementController::class, 'destroy'])->name('users.destroy');
        Route::patch('/users/{user}/toggle', [UserManagementController::class, 'toggleActive'])->name('users.toggle');
    });

    // Notifications
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [NotificationController::class, 'readAll'])->name('notifications.read-all');
});

// ─── API Routes (internal, no auth for now — dental chart) ───────────────────

Route::middleware(['auth', 'clinic.access', 'subscription.active'])
     ->prefix('api')
     ->group(function () {
    Route::post('/patients/{patient}/dental-chart', [DentalChartController::class, 'store'])->name('api.dental-chart.store');
});
