<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\StockMovementController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'role:admin'])->group(function () {
    // Dashboard Routes
    Route::get('/admin/dashboard', [AdminController::class, 'index'])
        ->name('admin.dashboard');

    // Category Routes
    Route::get('/admin/category', [CategoryController::class, 'index'])->name('admin.category.index');
    Route::post('/admin/category', [CategoryController::class, 'store'])->name('admin.category.store');
    Route::put('/admin/category/{category}', [CategoryController::class, 'update'])->name('admin.category.update');
    Route::delete('/admin/category/{id}', [CategoryController::class, 'destroy'])->name('admin.category.destroy');

    // Unit Routes
    Route::get('/admin/unit', [UnitController::class, 'index'])->name('admin.unit.index');
    Route::post('/admin/unit', [UnitController::class, 'store'])->name('admin.unit.store');
    Route::put('/admin/unit/{unit}', [UnitController::class, 'update'])->name('admin.unit.update');
    Route::delete('/admin/unit/{id}', [UnitController::class, 'destroy'])->name('admin.unit.destroy');

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

});

Route::middleware(['auth', 'role:user'])
    ->group(function () {
        Route::get('/dashboard', [UserController::class, 'index'])
            ->name('dashboard'); // 👈 IMPORTANT

        // Category
        Route::get('/category', [CategoryController::class, 'index'])->name('category.index');
        Route::post('/category', [CategoryController::class, 'store'])->name('category.store');
        Route::put('/category/{category}', [CategoryController::class, 'update'])->name('category.update');
        Route::delete('/category/{id}', [CategoryController::class, 'destroy'])->name('category.destroy');

        Route::get('/unit', [UnitController::class, 'index'])->name('unit.index');
        Route::post('/unit', [UnitController::class, 'store'])->name('unit.store');
        Route::put('/unit/{unit}', [UnitController::class, 'update'])->name('unit.update');
        route::delete('/unit/{id}', [UnitController::class, 'destroy'])->name('unit.destroy');

        // Supplier Routes
        Route::get('/supplier', [SupplierController::class, 'index'])->name('supplier.index');
        Route::get('/supplier/create', [SupplierController::class, 'create'])->name('supplier.create');
        Route::post('/supplier/create', [SupplierController::class, 'store'])->name('supplier.store');
        Route::get('/supplier/{supplier}/edit', [SupplierController::class, 'edit'])->name('supplier.edit');
        Route::put('/supplier/{supplier}/edit', [SupplierController::class, 'update'])->name('supplier.update');
        route::delete('/supplier/{id}', [SupplierController::class, 'destroy'])->name('supplier.destroy');
    });

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
