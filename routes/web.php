<?php

use App\Http\Controllers\Admin\BrandSearchController;
use App\Http\Controllers\Admin\MeasurementUnitSearchController;
use App\Http\Controllers\Admin\ProductCategorySearchController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\JSON\BrandController;
use App\Http\Controllers\JSON\ProductCategoryController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/{slug?}', HomeController::class)->where('slug', 'home')->name('home');
Route::resource('products', \App\Http\Controllers\ProductController::class);

Route::prefix('json')->name('json.')->group(function () {
    Route::get('product_categories', ProductCategoryController::class)->name('product.categories');
    Route::get('brands', BrandController::class)->name('brands');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', function () {
            return Inertia::render('Admin/Dashboard');
        })->name('dashboard');

        Route::resource('users', UserController::class);
        Route::resource('products', ProductController::class)->except(['create', 'edit']);
        Route::get('brands_search', BrandSearchController::class)->name('brands.search');
        Route::get('measurement_units_search', MeasurementUnitSearchController::class)->name('measurement_units.search');
        Route::get('product_categories_search', ProductCategorySearchController::class)->name('product_categories.search');
    });
});

require __DIR__.'/auth.php';
