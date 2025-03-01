<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    CashFlowController,
    CategoryController,
    CustomerController,
    ProductController,
    SalesController,
    SupplierController,
    UploadController,
    RoleController,
    PermissionController,
    UserRolePermissionController,
    UserController,
    Auth\AuthenticatedSessionController
};

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
| Define all web routes for your application here.
| These routes are loaded by the RouteServiceProvider and belong
| to the "web" middleware group.
|--------------------------------------------------------------------------
*/

// Authentication routes
require __DIR__ . '/auth.php';

Route::get('/', fn() => redirect('/login'));
Route::get('/home', fn() => view('index'))->name('home')->middleware('auth');

// Role and Permission Management
Route::prefix('roles')->group(function () {
    Route::get('{role}/permissions/edit', [UserRolePermissionController::class, 'assignPermissionsToRole'])->name('roles.permissions.edit');
    Route::post('{role}/permissions/update', [UserRolePermissionController::class, 'updatePermissionsForRole'])->name('roles.permissions.update');
});

// Register RoleController routes outside the prefix
Route::resource('roles', RoleController::class)->except(['show']);

Route::resource('permissions', PermissionController::class)->except(['show']);

// User Role and Permission Assignment
Route::prefix('users')->group(function () {
    Route::get('{user}/roles', [UserRolePermissionController::class, 'editRoles'])->name('users.edit-roles');
    Route::put('{user}/roles', [UserRolePermissionController::class, 'updateRoles'])->name('users.update-roles');
    Route::get('{user}/permissions', [UserRolePermissionController::class, 'editPermissions'])->name('users.edit-permissions');
    Route::put('{user}/permissions', [UserRolePermissionController::class, 'updatePermissions'])->name('users.update-permissions');
});
Route::resource('users', UserController::class)->middleware(['check.permissions']);

// Category Hierarchy Routes
Route::get('categories/{category}/subcategories', [CategoryController::class, 'getSubcategories']);
Route::get('subcategories/{subcategory}/subsubcategories', [CategoryController::class, 'getSubSubcategories']);
Route::resource('categories', CategoryController::class)->middleware(['check.permissions']);

// Resource Management
Route::middleware('auth')->group(function () {
    Route::resource('customers', CustomerController::class);
    Route::resource('cash-flows', CashFlowController::class);
    Route::resource('products', ProductController::class);
    Route::resource('sales', SalesController::class);
    Route::resource('suppliers', SupplierController::class);
});

// Media Upload
Route::post('/upload-media', [UploadController::class, 'upload'])->name('upload-media');
Route::delete('/delete-media/{media}', [UploadController::class, 'delete'])->name('delete-media');

// API Routes
Route::prefix('api')->group(function () {
    Route::get('customers', [CustomerController::class, 'indexJson'])->name('api.customers.index');
    Route::get('suppliers', [SupplierController::class, 'indexJson'])->name('api.suppliers.index');
    Route::get('cash-flows', [CashFlowController::class, 'indexJson'])->name('api.cash-flows.index');
    Route::get('products', [ProductController::class, 'indexJson'])->name('api.products.index');
});// Static Views
$staticViews = [
    'charts' => 'charts',
    'apps/calendar' => 'apps.calender',
    'apps/tickets' => 'apps.tickets',
    'apps/file-manager' => 'apps.file-manager',
    'apps/kanban' => 'apps.kanban',
    'project/list' => 'project.list',
    'project/detail' => 'project.detail',
    'project/create' => 'project.create',
    'auth/login' => 'auth.login',
    'auth/register' => 'auth.register',
    'auth/recoverpw' => 'auth.recoverpw',
    'auth/lock-screen' => 'auth.lock-screen',
    'pages/starter' => 'pages.starter',
    'pages/timeline' => 'pages.timeline',
    'pages/invoice' => 'pages.invoice',
    'pages/gallery' => 'pages.gallery',
    'pages/faqs' => 'pages.faqs',
    'pages/pricing' => 'pages.pricing',
    'pages/maintenance' => 'pages.maintenance',
    'pages/coming-soon' => 'pages.coming-soon',
    'pages/404' => 'pages.404',
    'pages/404-alt' => 'pages.404-alt',
    'pages/500' => 'pages.500',
];

foreach ($staticViews as $route => $view) {
    Route::view($route, $view)->name(str_replace('/', '.', $route));
}

// Simplified miscellaneous UI and extended features
Route::view('/map', 'map');
Route::view('/ui/alerts', 'ui.alerts')->name('ui.alerts');
Route::view('/forms/elements', 'forms.elements')->name('forms.elements');
Route::view('/tables/basic', 'tables.basic')->name('tables.basic');
Route::view('/icons/feather', 'icons.feather')->name('icons.feather');
Route::view('/maps/google', 'maps.google')->name('maps.google');
Route::get('/layouts-eg/hover-view', function () {
    return view('layouts-eg.hover-view');
})->name('layouts-eg.hover-view');
Route::view('/layouts-eg/icon-view', 'layouts-eg.icon-view')->name('layouts-eg.icon-view');
Route::view('/layouts-eg/compact-view', 'layouts-eg.compact-view')->name('layouts-eg.compact-view');
Route::view('/layouts-eg/mobile-view', 'layouts-eg.mobile-view')->name('layouts-eg.mobile-view');
Route::view('/layouts-eg/hidden-view', 'layouts-eg.hidden-view')->name('layouts-eg.hidden-view');
Route::view('/ui/accordions', 'ui.accordions')->name('ui.accordions');
Route::view('/ui/alerts', 'ui.alerts')->name('ui.alerts');
Route::view('/ui/avatars', 'ui.avatars')->name('ui.avatars');
Route::view('/ui/buttons', 'ui.buttons')->name('ui.buttons');
Route::view('/ui/badges', 'ui.badges')->name('ui.badges');
Route::view('/ui/breadcrumbs', 'ui.breadcrumbs')->name('ui.breadcrumbs');
Route::view('/ui/cards', 'ui.cards')->name('ui.cards');
Route::view('/ui/collapse', 'ui.collapse')->name('ui.collapse');
Route::view('/ui/dismissible', 'ui.dismissible')->name('ui.dismissible');
Route::view('/ui/dropdowns', 'ui.dropdowns')->name('ui.dropdowns');
Route::view('/ui/progress', 'ui.progress')->name('ui.progress');
Route::view('/ui/skeleton', 'ui.skeleton')->name('ui.skeleton');
Route::view('/ui/spinners', 'ui.spinners')->name('ui.spinners');
Route::view('/ui/list-group', 'ui.list-group')->name('ui.list-group');
Route::view('/ui/ratio', 'ui.ratio')->name('ui.ratio');
Route::view('/ui/tabs', 'ui.tabs')->name('ui.tabs');
Route::view('/ui/modals', 'ui.modals')->name('ui.modals');
Route::view('/ui/offcanvas', 'ui.offcanvas')->name('ui.offcanvas');
Route::view('/ui/popovers', 'ui.popovers')->name('ui.popovers');
Route::view('/ui/tooltips', 'ui.tooltips')->name('ui.tooltips');
Route::view('/ui/typography', 'ui.typography')->name('ui.typography');

Route::view('/extended/swiper', 'extended.swiper')->name('extended.swiper');
Route::view('/extended/nestable', 'extended.nestable')->name('extended.nestable');
Route::view('/extended/ratings', 'extended.ratings')->name('extended.ratings');
Route::view('/extended/animation', 'extended.animation')->name('extended.animation');
Route::view('/extended/player', 'extended.player')->name('extended.player');
Route::view('/extended/scrollbar', 'extended.scrollbar')->name('extended.scrollbar');
Route::view('/extended/sweet-alert', 'extended.sweet-alert')->name('extended.sweet-alert');
Route::view('/extended/tour', 'extended.tour')->name('extended.tour');
Route::view('/extended/tippy-tooltips', 'extended.tippy-tooltips')->name('extended.tippy-tooltips');
Route::view('/extended/lightbox', 'extended.lightbox')->name('extended.lightbox');

Route::view('/forms/elements', 'forms.elements')->name('forms.elements');
Route::view('/forms/select', 'forms.select')->name('forms.select');
Route::view('/forms/range', 'forms.range')->name('forms.range');
Route::view('/forms/pickers', 'forms.pickers')->name('forms.pickers');
Route::view('/forms/masks', 'forms.masks')->name('forms.masks');
Route::view('/forms/editor', 'forms.editor')->name('forms.editor');
Route::view('/forms/file-uploads', 'forms.file-uploads')->name('forms.file-uploads');
Route::view('/forms/validation', 'forms.validation')->name('forms.validation');
Route::view('/forms/layout', 'forms.layout')->name('forms.layout');

Route::view('/tables/basic', 'tables.basic')->name('tables.basic');
Route::view('/tables/datatables', 'tables/datatables')->name('tables.datatables');

Route::view('/icons/mingcute', 'icons.mingcute')->name('icons.mingcute');
Route::view('/icons/feather', 'icons.feather')->name('icons.feather');
Route::view('/icons/material-symbols', 'icons.material-symbols')->name('icons.material-symbols');

Route::view('/charts', 'charts.index')->name('charts');

Route::view('/maps/google', 'maps.google')->name('maps.google');
