<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\FrontendController;
use Illuminate\Support\Facades\Route;
use Spatie\Permission\Models\Role;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/news', [NewsController::class, 'index'])->name('news.index');
Route::get('/news/{slug}', [NewsController::class, 'show'])->name('news.show');

Route::get('/services', [ServiceController::class, 'index'])->name('services.index');
Route::get('/services/{id}', [ServiceController::class, 'show'])->name('services.show');

Route::get('/contact', [ContactController::class, 'index'])->name('contact.index');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

Route::get('/about', [FrontendController::class, 'about'])->name('about');
Route::get('/products-services', [FrontendController::class, 'productsServices'])->name('products-services');
Route::get('/service-areas', [FrontendController::class, 'serviceAreas'])->name('service-areas');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Dashboard routes par rôle (corrigé)
Route::get('/manager/dashboard', [\App\Http\Controllers\UserController::class, 'managerDashboard'])
    ->middleware(['auth', 'role:manager'])
    ->name('manager.dashboard');

Route::get('/editor/dashboard', [\App\Http\Controllers\UserController::class, 'editorDashboard'])
    ->middleware(['auth', 'role:editor'])
    ->name('editor.dashboard');

Route::get('/viewer/dashboard', [\App\Http\Controllers\UserController::class, 'viewerDashboard'])
    ->middleware(['auth', 'role:viewer'])
    ->name('viewer.dashboard');

Route::get('/user/dashboard', [\App\Http\Controllers\UserController::class, 'userDashboard'])
    ->middleware(['auth', 'role:user'])
    ->name('user.dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/profile/export', [ProfileController::class, 'export'])->name('profile.export');
    Route::get('/account/delete', [AccountController::class, 'showDeleteForm'])->name('account.delete.form');
    Route::delete('/account/delete', [AccountController::class, 'deleteAccount'])->name('account.delete');
});

// Admin (et super admin) dashboard route corrigée
Route::get('/admin', [\App\Http\Controllers\AdminController::class, 'index'])
    ->middleware(['auth', 'role:admin'])
    ->name('admin.index');

// Admin Routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [\App\Http\Controllers\AdminController::class, 'index'])->name('index');

    // Roles CRUD
    Route::middleware('permission:view roles')->get('/roles', [\App\Http\Controllers\AdminController::class, 'rolesIndex'])->name('roles.index');
    Route::middleware('permission:create roles')->get('/roles/create', [\App\Http\Controllers\AdminController::class, 'rolesCreate'])->name('roles.create');
    Route::middleware('permission:create roles')->post('/roles', [\App\Http\Controllers\AdminController::class, 'rolesStore'])->name('roles.store');
    Route::middleware('permission:edit roles')->get('/roles/{role}/edit', [\App\Http\Controllers\AdminController::class, 'rolesEdit'])->name('roles.edit');
    Route::middleware('permission:edit roles')->put('/roles/{role}', [\App\Http\Controllers\AdminController::class, 'rolesUpdate'])->name('roles.update');
    Route::middleware('permission:delete roles')->delete('/roles/{role}', [\App\Http\Controllers\AdminController::class, 'rolesDestroy'])->name('roles.destroy');

    // Groups CRUD
    Route::middleware('permission:view groups')->get('/groups', [\App\Http\Controllers\AdminController::class, 'groupsIndex'])->name('groups.index');
    Route::middleware('permission:create groups')->get('/groups/create', [\App\Http\Controllers\AdminController::class, 'groupsCreate'])->name('groups.create');
    Route::middleware('permission:create groups')->post('/groups', [\App\Http\Controllers\AdminController::class, 'groupsStore'])->name('groups.store');
    Route::middleware('permission:edit groups')->get('/groups/{group}/edit', [\App\Http\Controllers\AdminController::class, 'groupsEdit'])->name('groups.edit');
    Route::middleware('permission:edit groups')->put('/groups/{group}', [\App\Http\Controllers\AdminController::class, 'groupsUpdate'])->name('groups.update');
    Route::middleware('permission:delete groups')->delete('/groups/{group}', [\App\Http\Controllers\AdminController::class, 'groupsDestroy'])->name('groups.destroy');

    // News CRUD
    Route::middleware('permission:view news')->get('/news', [\App\Http\Controllers\AdminController::class, 'newsIndex'])->name('news.index');
    Route::middleware('permission:create news')->get('/news/create', [\App\Http\Controllers\AdminController::class, 'newsCreate'])->name('news.create');
    Route::middleware('permission:create news')->post('/news', [\App\Http\Controllers\AdminController::class, 'newsStore'])->name('news.store');
    Route::middleware('permission:view news')->get('/news/{news}', [\App\Http\Controllers\AdminController::class, 'newsShow'])->name('news.show');
    Route::middleware('permission:edit news')->get('/news/{news}/edit', [\App\Http\Controllers\AdminController::class, 'newsEdit'])->name('news.edit');
    Route::middleware('permission:edit news')->put('/news/{news}', [\App\Http\Controllers\AdminController::class, 'newsUpdate'])->name('news.update');
    Route::middleware('permission:delete news')->delete('/news/{news}', [\App\Http\Controllers\AdminController::class, 'newsDestroy'])->name('news.destroy');

    // Services CRUD
    Route::middleware('permission:view services')->get('/services', [\App\Http\Controllers\AdminController::class, 'servicesIndex'])->name('services.index');
    Route::middleware('permission:create services')->get('/services/create', [\App\Http\Controllers\AdminController::class, 'servicesCreate'])->name('services.create');
    Route::middleware('permission:create services')->post('/services', [\App\Http\Controllers\AdminController::class, 'servicesStore'])->name('services.store');
    Route::middleware('permission:view services')->get('/services/{service}', [\App\Http\Controllers\AdminController::class, 'servicesShow'])->name('services.show');
    Route::middleware('permission:edit services')->get('/services/{service}/edit', [\App\Http\Controllers\AdminController::class, 'servicesEdit'])->name('services.edit');
    Route::middleware('permission:edit services')->put('/services/{service}', [\App\Http\Controllers\AdminController::class, 'servicesUpdate'])->name('services.update');
    Route::middleware('permission:delete services')->delete('/services/{service}', [\App\Http\Controllers\AdminController::class, 'servicesDestroy'])->name('services.destroy');

    // Contacts CRUD
    Route::middleware('permission:view contacts')->get('/contacts', [\App\Http\Controllers\AdminController::class, 'contactsIndex'])->name('contacts.index');
    Route::middleware('permission:view contacts')->get('/contacts/{contact}', [\App\Http\Controllers\AdminController::class, 'contactsShow'])->name('contacts.show');
    Route::middleware('permission:delete contacts')->delete('/contacts/{contact}', [\App\Http\Controllers\AdminController::class, 'contactsDestroy'])->name('contacts.destroy');

    // Security & Logs
    Route::middleware('permission:view security logs')->get('/security/logs', [\App\Http\Controllers\AdminController::class, 'securityLogs'])->name('security.logs');
    Route::middleware('permission:view security dashboard')->get('/security/dashboard', [\App\Http\Controllers\AdminController::class, 'securityDashboard'])->name('security.dashboard');
    Route::middleware('permission:view security checklist')->get('/security/checklist', [\App\Http\Controllers\AdminController::class, 'securityChecklist'])->name('security.checklist');
});

require __DIR__.'/auth.php';
