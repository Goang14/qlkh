<?php

use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\RepairController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\SupplierController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::group(['middleware' => ['auth', 'verified']], function() {
    Route::get('/', [HomeController::class, 'index'])->name('home');

    Route::get('/suppliers', [SupplierController::class, 'index'])->name('suppliers');
    Route::post('/create-suppliers', [SupplierController::class, 'store'])->name('suppliers.create');
    Route::post('/update-suppliers', [SupplierController::class, 'update'])->name('suppliers.update');
    Route::get('/search-suppliers', [SupplierController::class, 'searchSupplier'])->name('suppliers.search');
    Route::delete('/delete-suppliers/{id}', [SupplierController::class, 'destroy'])->name('suppliers.delete');

    ////====////
    Route::get('/product', [ProductController::class, 'index'])->name('product');
    Route::post('/create-product', [ProductController::class, 'store'])->name('product.create');
    Route::get('/search-product', [ProductController::class, 'searchProduct'])->name('product.search');
    Route::post('/update-product', [ProductController::class, 'update'])->name('product.update');
    Route::delete('/delete-product/{id}', [ProductController::class, 'destroy'])->name('product.delete');


    //===//
    Route::get('/repair', [RepairController::class, 'index'])->name('repairs');
    Route::post('/create-repair', [RepairController::class, 'store'])->name('repairs.create');
    Route::post('/update-repair', [RepairController::class, 'update'])->name('repairs.update');
    Route::get('/search-repair', [RepairController::class, 'searchRepair'])->name('repairs.search');
    Route::delete('/delete-repair/{id}', [RepairController::class, 'destroy'])->name('repair.delete');


    Route::get('/customer', [CustomerController::class, 'index'])->name('customer');

});
