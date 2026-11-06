<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ForecastController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\FinanceController;

Route::get('/', fn () => !auth()->check() ? redirect('/login') : (auth()->user()->isOwner() ? redirect()->route('owner.dashboard') : (auth()->user()->isAdmin() ? redirect()->route('admin.dashboard') : redirect()->route('employee.dashboard'))));

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.store');
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

Route::middleware(['auth', 'role:owner'])->group(function () {
    Route::get('/owner/dashboard', [App\Http\Controllers\OwnerDashboardController::class, 'index'])->name('owner.dashboard');
});

Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');

    Route::get('/products', [ProductController::class, 'index'])->name('admin.products');
    Route::post('/products', [ProductController::class, 'store'])->name('admin.products.store');
    Route::put('/products/{product}', [ProductController::class, 'update'])->name('admin.products.update');
    Route::patch('/products/{product}/toggle', [ProductController::class, 'toggle'])->name('admin.products.toggle');

    Route::get('/categories', [CategoryController::class, 'index'])->name('admin.categories');
    Route::post('/categories', [CategoryController::class, 'store'])->name('admin.categories.store');
    Route::put('/categories/{category}', [CategoryController::class, 'update'])->name('admin.categories.update');
    Route::patch('/categories/{category}/toggle', [CategoryController::class, 'toggle'])->name('admin.categories.toggle');

    Route::get('/stocks', [StockController::class, 'index'])->name('admin.stocks');
    Route::post('/stocks/{product}/in', [StockController::class, 'stockIn'])->name('admin.stocks.in');
    Route::post('/stocks/{product}/out', [StockController::class, 'stockOut'])->name('admin.stocks.out');
    Route::get('/stock-movements', [StockController::class, 'movements'])->name('admin.stock-movements');

    Route::get('/transactions', [TransactionController::class, 'index'])->name('admin.transactions');
    Route::get('/transactions/{sale}', [TransactionController::class, 'show'])->name('admin.transactions.show');

    Route::get('/reports/sales', [ReportController::class, 'sales'])->name('admin.reports.sales');
    Route::get('/reports/profit', [ReportController::class, 'profit'])->name('admin.reports.profit');
    Route::get('/analytics', [ReportController::class, 'analytics'])->name('admin.analytics');

    Route::get('/forecasts', [ForecastController::class, 'index'])->name('admin.forecasts');
    Route::post('/forecasts/run', [ForecastController::class, 'run'])->name('admin.forecasts.run');
    Route::get('/forecasts/import', [ForecastController::class, 'import'])->name('admin.forecasts.import');
    Route::post('/forecasts/import', [ForecastController::class, 'importStore'])->name('admin.forecasts.import.store');

    Route::get('/restock', [ForecastController::class, 'restock'])->name('admin.restock');
    Route::post('/restock/copy', [ForecastController::class, 'copyRestock'])->name('admin.restock.copy');

    Route::get('/finance', [FinanceController::class, 'index'])->name('admin.finance');
    Route::post('/finance', [FinanceController::class, 'store'])->name('admin.finance.store');
    Route::delete('/finance/{cashTransaction}', [FinanceController::class, 'destroy'])->name('admin.finance.destroy');

    Route::get('/employees', [EmployeeController::class, 'index'])->name('admin.employees');
    Route::post('/employees', [EmployeeController::class, 'store'])->name('admin.employees.store');
    Route::put('/employees/{user}', [EmployeeController::class, 'update'])->name('admin.employees.update');
    Route::patch('/employees/{user}/toggle', [EmployeeController::class, 'toggle'])->name('admin.employees.toggle');

    Route::get('/activity-logs', [ActivityLogController::class, 'index'])->name('admin.activity-logs');
});

Route::middleware(['auth', 'role:employee'])->prefix('employee')->group(function () {
    Route::get('/dashboard', [TransactionController::class, 'employeeDashboard'])->name('employee.dashboard');
    Route::get('/transactions', [TransactionController::class, 'pos'])->name('employee.transactions');
    Route::get('/transactions/search', [TransactionController::class, 'searchProducts'])->name('employee.transactions.search');
    Route::post('/transactions', [TransactionController::class, 'store'])->name('employee.transactions.store');
    Route::get('/stocks', [StockController::class, 'employeeStocks'])->name('employee.stocks');
    Route::get('/today-sales', [TransactionController::class, 'todaySales'])->name('employee.today-sales');
});

Route::fallback(fn () => response()->view('errors.404', [], 404));
