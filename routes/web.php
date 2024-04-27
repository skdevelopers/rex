<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RoleController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoutingController;
use App\Http\Controllers\PermissionController;

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

require __DIR__ . '/auth.php';

    // Resource routes for managing permissions
    Route::resource('permissions', PermissionController::class)->names([
        'index' => 'permissions.index',
        'create' => 'permissions.create',
        'store' => 'permissions.store',
        'show' => 'permissions.show',
        'edit' => 'permissions.edit', // This line is important for generating the edit route
        'update' => 'permissions.update',
        'destroy' => 'permissions.destroy',
    ])->middleware('auth');

Route::get('categories/{category}/subcategories', [CategoryController::class, 'getSubcategories'])
    ->name('categories.subcategories');

Route::resource('categories', CategoryController::class)->middleware('auth');

// Define routes for the RoleController outside of the permissions group
Route::resource('roles', RoleController::class)->middleware('auth');
// Define routes for the RoleController outside of the permissions group
Route::resource('customers', CustomerController::class)->middleware('auth');
// Define routes for the RoleController outside of the permissions group
Route::resource('products', ProductController::class)->middleware('auth');


// Your other routes
Route::group(['prefix' => '/', 'middleware' => 'auth'], function () {
    Route::get('', [RoutingController::class, 'index'])->name('root');
    Route::get('/home', fn() => view('index'))->name('home');
    Route::get('{first}/{second}/{third}', [RoutingController::class, 'thirdLevel'])->name('third');
    Route::get('{first}/{second}', [RoutingController::class, 'secondLevel'])->name('second');
    Route::get('{any}', [RoutingController::class, 'root'])->name('any');
});

