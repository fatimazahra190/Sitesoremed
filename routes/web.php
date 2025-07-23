<?php



use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\{
    AdminController, GroupController, RoleController, ServiceController, NewsController,
    ContactController, FrontendController, HomeController, AccountController, UserController
};

// Page d'accueil et frontend
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', [FrontendController::class, 'about'])->name('about');
Route::get('/contact', [FrontendController::class, 'contact'])->name('contact.index');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');
Route::get('/news', [FrontendController::class, 'news'])->name('news.index');
Route::get('/products-services', [FrontendController::class, 'productsServices'])->name('products-services');
Route::get('/service-areas', [FrontendController::class, 'serviceAreas'])->name('service-areas');
Route::get('/services', [FrontendController::class, 'services'])->name('services.index');

// Authentification
Auth::routes();

// Utilisateur connecté
Route::middleware(['auth'])->prefix('user')->name('user.')->group(function () {
    Route::get('/', [UserController::class, 'index'])->name('index');
    Route::get('/dashboard', [UserController::class, 'userDashboard'])->name('dashboard');
    Route::get('/account/delete', [AccountController::class, 'showDeleteForm'])->name('account.delete.form');
    Route::post('/account/delete', [AccountController::class, 'deleteAccount'])->name('account.delete');
});

// Admin panel (RBAC + permissions)
Route::middleware(['auth', 'permission:access admin panel'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('index');

    // Utilisateurs
    Route::middleware('permission:view users')->group(function () {
        Route::resource('users', AdminController::class)->except(['edit', 'update', 'destroy']);
        Route::get('users/{user}/edit', [AdminController::class, 'editUser'])->name('users.edit')->middleware('permission:edit users');
        Route::put('users/{user}', [AdminController::class, 'updateUser'])->name('users.update')->middleware('permission:edit users');
        Route::delete('users/{user}', [AdminController::class, 'deleteUser'])->name('users.delete')->middleware('permission:delete users');
        Route::delete('users/{user}/permanent', [AdminController::class, 'permanentlyDeleteUser'])->name('users.permanent-delete')->middleware('permission:delete users');
        Route::get('users/{user}/assign-roles', [AdminController::class, 'assignRolesForm'])->name('users.assign-roles-form')->middleware('permission:assign roles');
        Route::post('users/{user}/assign-roles', [AdminController::class, 'assignUserRole'])->name('users.assign-roles')->middleware('permission:assign roles');
        Route::post('users/{user}/suspend', [AdminController::class, 'suspendUser'])->name('users.suspend')->middleware('permission:suspend users');
        Route::get('users/{user}/export', [AdminController::class, 'exportUserData'])->name('users.export')->middleware('permission:export data');
        Route::get('users/export-all', [AdminController::class, 'exportUsers'])->name('users.export-all')->middleware('permission:export data');
        Route::post('users/import', [AdminController::class, 'importUsers'])->name('users.import')->middleware('permission:import users');
        Route::post('users/{user}/reset-password', [AdminController::class, 'resetUserPassword'])->name('users.reset-password')->middleware('permission:edit users');
        Route::post('users/{user}/toggle-status', [AdminController::class, 'toggleUserStatus'])->name('users.toggle-status')->middleware('permission:edit users');
        Route::post('users/{user}/enable-mfa', [AdminController::class, 'enableMFA'])->name('users.enable-mfa')->middleware('permission:manage security');
        Route::post('users/{user}/disable-mfa', [AdminController::class, 'disableMFA'])->name('users.disable-mfa')->middleware('permission:manage security');
        Route::post('users/{user}/force-password-change', [AdminController::class, 'forcePasswordChange'])->name('users.force-password-change')->middleware('permission:manage security');
    });

    // Rôles
    Route::middleware('permission:view roles')->group(function () {
        Route::resource('roles', RoleController::class)->except(['edit', 'update', 'destroy']);
        Route::get('roles/{role}/edit', [RoleController::class, 'edit'])->name('roles.edit')->middleware('permission:edit roles');
        Route::put('roles/{role}', [RoleController::class, 'update'])->name('roles.update')->middleware('permission:edit roles');
        Route::delete('roles/{role}', [RoleController::class, 'destroy'])->name('roles.destroy')->middleware('permission:delete roles');
    });

    // Groupes
    Route::middleware('permission:view groups')->group(function () {
        Route::resource('groups', GroupController::class)->except(['edit', 'update', 'destroy']);
        Route::get('groups/{group}/edit', [GroupController::class, 'edit'])->name('groups.edit')->middleware('permission:edit groups');
        Route::put('groups/{group}', [GroupController::class, 'update'])->name('groups.update')->middleware('permission:edit groups');
        Route::delete('groups/{group}', [GroupController::class, 'destroy'])->name('groups.destroy')->middleware('permission:delete groups');
        Route::get('groups/{group}/assign-users', [GroupController::class, 'assignUsersForm'])->name('groups.assign-users-form')->middleware('permission:assign users to groups');
        Route::post('groups/{group}/assign-users', [GroupController::class, 'assignUsers'])->name('groups.assign-users')->middleware('permission:assign users to groups');
        Route::get('groups/{group}/assign-roles', [GroupController::class, 'assignRolesForm'])->name('groups.assign-roles-form')->middleware('permission:assign roles to groups');
        Route::post('groups/{group}/assign-roles', [GroupController::class, 'assignRoles'])->name('groups.assign-roles')->middleware('permission:assign roles to groups');
    });

    // Services
    Route::middleware('permission:view services')->group(function () {
        Route::resource('services', ServiceController::class)->except(['edit', 'update', 'destroy']);
        Route::get('services/{service}/edit', [ServiceController::class, 'edit'])->name('services.edit')->middleware('permission:edit services');
        Route::put('services/{service}', [ServiceController::class, 'update'])->name('services.update')->middleware('permission:edit services');
        Route::delete('services/{service}', [ServiceController::class, 'destroy'])->name('services.destroy')->middleware('permission:delete services');
    });

    // News
    Route::middleware('permission:view news')->group(function () {
        Route::resource('news', NewsController::class)->except(['edit', 'update', 'destroy']);
        Route::get('news/{news}/edit', [NewsController::class, 'edit'])->name('news.edit')->middleware('permission:edit news');
        Route::put('news/{news}', [NewsController::class, 'update'])->name('news.update')->middleware('permission:edit news');
        Route::delete('news/{news}', [NewsController::class, 'destroy'])->name('news.destroy')->middleware('permission:delete news');
    });

    // Contacts
    Route::middleware('permission:view contacts')->group(function () {
        Route::resource('contacts', ContactController::class)->except(['edit', 'update', 'destroy']);
        Route::post('contacts/{contact}/reply', [ContactController::class, 'reply'])->name('contacts.reply')->middleware('permission:reply contacts');
        Route::delete('contacts/{contact}', [ContactController::class, 'destroy'])->name('contacts.destroy')->middleware('permission:delete contacts');
    });

    // Sécurité
    Route::middleware('permission:view security dashboard')->get('security-dashboard', [AdminController::class, 'securityDashboard'])->name('security-dashboard');
    Route::middleware('permission:manage sessions')->group(function () {
        Route::get('session-management', [AdminController::class, 'sessionManagement'])->name('session-management');
        Route::post('session-management/terminate', [AdminController::class, 'terminateSession'])->name('session-management.terminate');
    });
    Route::middleware('permission:manage ip whitelist')->group(function () {
        Route::get('ip-whitelist', [AdminController::class, 'ipWhitelist'])->name('ip-whitelist');
        Route::post('ip-whitelist', [AdminController::class, 'addIPToWhitelist'])->name('ip-whitelist.add');
        Route::delete('ip-whitelist/{ip}', [AdminController::class, 'removeIPFromWhitelist'])->name('ip-whitelist.remove');
    });
    Route::middleware('permission:view security logs')->get('security-logs', [AdminController::class, 'securityLogs'])->name('security-logs');
    Route::middleware('permission:view security checklist')->get('security-checklist', [AdminController::class, 'securityChecklist'])->name('security-checklist');
    Route::middleware('permission:send notifications')->post('security/notify', [AdminController::class, 'sendSecurityNotification'])->name('security.notify');
    Route::get('role-matrix', [AdminController::class, 'roleMatrix'])->name('role-matrix');

    // Logs avancés (super admin)
    Route::middleware('permission:super admin')->group(function () {
        Route::get('advanced-logs', [AdminController::class, 'advancedLogs'])->name('advanced-logs');
        Route::get('logs/{log}', [AdminController::class, 'logDetails'])->name('log-details');
        Route::get('suspicious-activity', [AdminController::class, 'suspiciousActivity'])->name('suspicious-activity');
    });
});

require __DIR__.'/auth.php';
