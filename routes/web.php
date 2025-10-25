<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BarcodeController;

use App\Http\Controllers\UserBarcodeController;

Route::get('/', function () {
    return view('welcome');
});

    Route::get('/dashboard',[UserController::class,'Dashboard'])->middleware(['auth', 'verified'])->name('dashboard');
//admin user

    Route::middleware(['auth','admin'])->group(function () {

    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');

    Route::get('/addcategory', [AdminController::class, 'addCategory'])->name('admin.addcategory');
    
    Route::post('/addcategory', [AdminController::class, 'postAddCategory'])->name('admin.postaddcategory');
    
    Route::get('/viewcategory', [AdminController::class, 'viewcategory'])->name('admin.viewcategory');
      
    Route::get('/deletecategory/{id}', [AdminController::class, 'deletecategory'])->name('admin.deletecategory');
    
    Route::get('/updatecategory/{id}', [AdminController::class, 'updateCategory'])->name('admin.updatecategory');

    Route::post('/updatecategory/{id}', [AdminController::class, 'postUpdateCategory'])->name('admin.postupdatecategory');
    
    Route::get('/addsupplier', [AdminController::class, 'addSupplier'])->name('admin.addsupplier');
    
    Route::post('/addsupplier', [AdminController::class, 'postAddSupplier'])->name('admin.postaddsupplier');

    Route::get('/viewsupplier', [AdminController::class, 'viewSupplier'])->name('admin.viewsupplier');

    Route::get('/deletesupplier/{id}', [AdminController::class, 'deleteSupplier'])->name('admin.deletesupplier');
    
    Route::get('/updatesupplier/{id}', [AdminController::class, 'updateSupplier'])->name('admin.updatesupplier');
    
    Route::post('/postupdatesupplier/{id}', [AdminController::class, 'postUpdateSupplier'])->name('admin.postupdatesupplier');

    Route::get('/addproduct', [AdminController::class, 'addProduct'])->name('admin.addproduct');
    
    Route::post('/addproduct', [AdminController::class, 'postAddProduct'])->name('admin.postaddproduct');

    Route::get('/viewproduct', [AdminController::class, 'viewProduct'])->name('admin.viewproduct');
    
    Route::get('/deleteproduct/{id}', [AdminController::class, 'deleteProduct'])->name('admin.deleteproduct');
    
    Route::get('/updateproduct/{id}', [AdminController::class, 'updateProduct'])->name('admin.updateproduct');

    Route::post('/postupdateproduct/{id}', [AdminController::class, 'postUpdateProduct'])->name('admin.postupdateproduct');

    // Admin Barcode Scanner Page
Route::get('/admin/barcode-scanner', [App\Http\Controllers\BarcodeController::class, 'scanner'])->name('admin.barcode.scanner');
Route::post('/admin/barcode/search', [BarcodeController::class, 'scan'])->name('admin.barcode.search');
Route::post('/admin/barcode/clear', [BarcodeController::class, 'clear'])->name('admin.barcode.clear');
Route::post('/admin/barcode/save-pdf', [BarcodeController::class, 'savePdf'])->name('admin.barcode.savePdf');
// Load saved scans
Route::get('/admin/barcode/load', [BarcodeController::class, 'load'])->name('admin.barcode.load');
Route::post('/admin/barcode/remove', [BarcodeController::class, 'remove'])->name('admin.barcode.remove');

    Route::post('/user/barcode/update-all', [UserBarcodeController::class, 'updateAllScans'])->name('user.barcode.updateAll');

Route::post('/admin/barcode/updateQuantity', [BarcodeController::class, 'updateQuantity'])->name('admin.barcode.updateQuantity');

});



// Normal user dashboard


Route::middleware(['auth'])->group(function () {
    // User dashboard
    Route::get('/user/dashboard', [UserController::class, 'dashboard'])->name('user.dashboard');
    Route::get('/user/barcode-scanner', [UserBarcodeController::class, 'index'])->name('user.barcode.scanner');
    Route::post('/user/barcode-scan', [UserBarcodeController::class, 'scan'])->name('user.barcode.scan');
    Route::get('/user/barcode-scans', [UserBarcodeController::class, 'getScans'])->name('user.barcode.scans');
    Route::delete('/user/barcode-scans', [UserBarcodeController::class, 'clearScans'])->name('user.barcode.clear');
});




Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class,'edit'])->name('profile.edit');
     Route::patch('/profile', [ProfileController::class,'update'])->name('profile.update');
      Route::delete('/profile', [ProfileController::class,'destroy'])->name('profile.destroy');
});

 
require __DIR__.'/auth.php';
