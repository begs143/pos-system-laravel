<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\PurchaseOrderController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SaleOrderController;
use App\Http\Controllers\StockMovementController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::fallback(function () {
    return auth()->check() ? redirect('/dashboard') : redirect('/login');
});

Route::middleware(['auth', 'role:admin'])->group(function () {
    // Dashboard Routes     // User Role Routes
    Route::get('/admin/dashboard', [AdminController::class, 'index'])
        ->name('admin.dashboard');
    Route::get('/admin/users', [AdminController::class, 'userRole'])->name('admin.user-role');
    Route::get('/admin/user/create', [AdminController::class, 'create'])->name('admin.user.create');
    Route::post('/admin/user/create', [AdminController::class, 'store'])->name('admin.user.store');
    Route::get('/admin/user/{id}/edit', [AdminController::class, 'edit'])->name('admin.user.edit');
    Route::put('/admin/user/{id}', [AdminController::class, 'update'])->name('admin.user.update');
    Route::delete('/admin/user/{id}', [AdminController::class, 'destroy'])->name('admin.user.destroy');

    // Category Routes
    Route::get('/admin/category', [CategoryController::class, 'index'])->name('admin.category.index');
    Route::post('/admin/category', [CategoryController::class, 'store'])->name('admin.category.store');
    Route::put('/admin/category/{category}', [CategoryController::class, 'update'])->name('admin.category.update');
    Route::delete('/admin/category/{id}', [CategoryController::class, 'destroy'])->name('admin.category.destroy');

    // Unit Routes
    Route::get('/admin/units', [UnitController::class, 'index'])->name('admin.units.index');
    Route::post('/admin/units', [UnitController::class, 'store'])->name('admin.units.store');
    Route::put('/admin/units/{unit}', [UnitController::class, 'update'])->name('admin.units.update');
    Route::delete('/admin/units/{id}', [UnitController::class, 'destroy'])->name('admin.units.destroy');

    // Supplier Routes
    Route::get('/admin/supplier', [SupplierController::class, 'index'])->name('admin.supplier.index');
    Route::get('/admin/supplier/create', [SupplierController::class, 'create'])->name('admin.supplier.create');
    Route::post('/admin/supplier/create', [SupplierController::class, 'store'])->name('admin.supplier.store');
    Route::get('/admin/supplier/{supplier}/edit', [SupplierController::class, 'edit'])->name('admin.supplier.edit');
    Route::put('/admin/supplier/{supplier}/edit', [SupplierController::class, 'update'])->name('admin.supplier.update');
    Route::delete('/admin/supplier/{id}', [SupplierController::class, 'destroy'])->name('admin.supplier.destroy');

    // Inventory Route
    Route::get('/admin/inventory', [InventoryController::class, 'index'])->name('admin.inventory.index');
    Route::get('/admin/inventory/create', [InventoryController::class, 'create'])->name('admin.inventory.create');
    Route::post('/admin/inventory/create', [InventoryController::class, 'store'])->name('admin.inventory.store');
    Route::get('/admin/inventory/{inventory}/edit', [InventoryController::class, 'edit'])->name('admin.inventory.edit');
    Route::put('/admin/inventory/{product}/edit', [InventoryController::class, 'update'])->name('admin.inventory.update');
    Route::delete('/admin/inventory/{id}', [InventoryController::class, 'destroy'])->name('admin.inventory.destroy');

    // Export inventory-files
    Route::get('/admin/inventory/export-excel', [InventoryController::class, 'export'])->name('admin.inventory.export');

    // Reports Routes
    Route::get('/admin/reports', [ReportController::class, 'index'])->name('admin.report.index');

    // Stock Movement Routes
    Route::get('/admin/stock-movement', [StockMovementController::class, 'index'])->name('admin.stockmovement.index');
    Route::post('/admin/stock-movement', [StockMovementController::class, 'store'])->name('admin.stockmovement.store');
    Route::get('/admin/stock-movement-list', [StockMovementController::class, 'show'])->name('admin.stockmovement.show');

    // Sale Routes
    Route::get('/admin/sale-orders', [SaleOrderController::class, 'index'])->name('admin.sale-orders.index');
    Route::get('/admin/sale-orders/summary', [SaleOrderController::class, 'summary'])->name('admin.sale-orders.summary');
    Route::post('/admin/sale-orders/summary', [SaleOrderController::class, 'store'])->name('admin.sale-orders.store');
    Route::get('/admin/sale-orders/{sale}/details', [SaleOrderController::class, 'details'])->name('admin.sale-orders.details');
    Route::get('/admin/sale-orders/{sale}/transactions', [SaleOrderController::class, 'downloadPDF'])->name('admin.sale-orders.view');
    Route::get('/admin/sale-orders/transactions', [SaleOrderController::class, 'transactions'])->name('admin.sale-orders.transactions');

    // Purchase Route
    Route::get('/admin/purchase-orders', [PurchaseOrderController::class, 'index'])->name('admin.purchase-orders.index');
    Route::get('/admin/purchase-orders/create', [PurchaseOrderController::class, 'create'])->name('admin.purchase-orders.create');
    Route::post('/admin/purchase-orders/create', [PurchaseOrderController::class, 'store'])->name('admin.purchase-orders.store');
    Route::put('/admin/purchase-orders/{id}', [PurchaseOrderController::class, 'update'])->name('admin.purchase-orders.update');
    Route::delete('/admin/purchase-orders/{id}', [PurchaseOrderController::class, 'destroy'])->name('admin.purchase-orders.destroy');
    Route::get('/admin/purchase-orders/{id}/view', [PurchaseOrderController::class, 'downloadPDF'])->name('admin.purchase-orders.pdf');

});

Route::middleware(['auth', 'role:user'])->group(function () {
    // Dashboard Routes
    Route::get('/dashboard', [UserController::class, 'index'])
        ->name('user.dashboard');

    // Sale Routes
    Route::get('/sale-orders', [SaleOrderController::class, 'index'])->name('user.sale-orders.index');
    Route::get('/sale-orders/summary', [SaleOrderController::class, 'summary'])->name('user.sale-orders.summary');
    Route::post('/sale-orders/summary', [SaleOrderController::class, 'store'])->name('user.sale-orders.store');
    Route::get('/sale-orders/{sale}/details', [SaleOrderController::class, 'details'])->name('user.sale-orders.details');
    Route::get('/sale-orders/{sale}/transactions', [SaleOrderController::class, 'downloadPDF'])->name('user.sale-orders.view');
    Route::get('/sale-orders/transactions', [SaleOrderController::class, 'transactions'])->name('user.sale-orders.transactions');
});

require __DIR__.'/auth.php';
