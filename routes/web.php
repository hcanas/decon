<?php

use App\Http\Controllers\Admin\BrandSearchController;
use App\Http\Controllers\Admin\Dashboard\CustomerReportController;
use App\Http\Controllers\Admin\Dashboard\ProductReportController;
use App\Http\Controllers\Admin\Dashboard\PurchaseOrderReportController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\MeasurementUnitSearchController;
use App\Http\Controllers\Admin\ProductCategorySearchController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\Profile\ActivityLogController;
use App\Http\Controllers\Admin\Profile\ChangePasswordController;
use App\Http\Controllers\Admin\Profile\AccountInformationController;
use App\Http\Controllers\Admin\PurchaseOrderController;
use App\Http\Controllers\Admin\QuotationController;
use App\Http\Controllers\Admin\QuotationItemController;
use App\Http\Controllers\Admin\UserActivityLogController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CartItemController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductCategoryController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

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
Route::get('cart', CartController::class)->name('cart');
Route::resource('cart_items', CartItemController::class);
Route::post('quotations', \App\Http\Controllers\QuotationController::class)->name('quotations.store');
Route::get('brands', BrandController::class)->name('brands');
Route::get('categories', ProductCategoryController::class)->name('categories');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('profile', [AccountInformationController::class, 'edit'])->name('profile.edit');
    Route::patch('profile', [AccountInformationController::class, 'update'])->name('profile.update');
    Route::get('profile/change_password', [ChangePasswordController::class, 'edit'])->name('profile.change_password');
    Route::get('profile/activity_log', ActivityLogController::class)->name('profile.activity_log');

    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', DashboardController::class)->name('dashboard');

        Route::prefix('reports')->name('reports.')->group(function () {
            Route::get('purchase_orders', PurchaseOrderReportController::class)->name('purchase_orders');
            Route::get('products', ProductReportController::class)->name('products');
            Route::get('customers', CustomerReportController::class)->name('customers');
        });

        Route::get('users/{user}/activity_log', UserActivityLogController::class)->name('user_activity_log');
        Route::resource('users', UserController::class);
        Route::resource('products', ProductController::class)->except(['create', 'edit']);
        Route::get('brands_search', BrandSearchController::class)->name('brands.search');
        Route::get('measurement_units_search', MeasurementUnitSearchController::class)->name('measurement_units.search');
        Route::get('product_categories_search', ProductCategorySearchController::class)->name('product_categories.search');
        Route::resource('purchase_orders', PurchaseOrderController::class);
        Route::resource('quotations', QuotationController::class);
        Route::match(['put', 'patch'], 'quotation_items', QuotationItemController::class)->name('quotation_items.update');
    });
});

require __DIR__.'/auth.php';
