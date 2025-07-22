<?php

namespace App\Http\Controllers;

use App\Models\News;
use App\Models\Service;
use App\Models\Contact;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Models\SecurityLog;
use Illuminate\Support\Facades\Hash;
use App\Mail\UserInvitation;
use App\Mail\PasswordReset;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class AdminController extends Controller
{
    public function index()
    {
        SecurityLog::create([
            'user_id' => auth()->id(),
            'action' => 'admin_panel_access',
            'ip_address' => request()->ip(),
            'details' => null,
        ]);
        $stats = [
            'news_count' => News::count(),
            'services_count' => Service::count(),
            'contacts_count' => Contact::count(),
            'recent_contacts' => Contact::latest()->take(5)->get(),
        ];
        
        return view("admin.index", compact('stats'));
    }

    public function users(Request $request)
    {
        $query = User::query();
        // Recherche
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                  ->orWhere('email', 'like', "%$search%") ;
            });
        }
        // Filtres
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }
        if ($request->filled('utype')) {
            $query->where('utype', $request->input('utype'));
        }
        if ($request->filled('group_id')) {
            $query->whereHas('groups', function($q) use ($request) {
                $q->where('groups.id', $request->input('group_id'));
            });
        }
        if ($request->filled('role')) {
            $query->whereHas('roles', function($q) use ($request) {
                $q->where('roles.id', $request->input('role'));
            });
        }
        $users = $query->orderBy('id', 'desc')->paginate(10)->withQueryString();
        $roles = Role::all();
        $groups = \App\Models\Group::all();
        return view('admin.users.index', compact('users', 'roles', 'groups'));
    }

    public function assignUserRole(Request $request, User $user)
    {
        $request->validate([
            'roles' => 'required|array',
            'roles.*' => 'exists:roles,id'
        ]);
        // Convert role IDs to names
        $roleNames = \Spatie\Permission\Models\Role::whereIn('id', $request->roles)->pluck('name')->toArray();
        // Supprimer les anciens rôles et assigner les nouveaux
        $user->syncRoles($roleNames);
        // Synchroniser utype avec le premier rôle assigné
        $user->utype = $roleNames[0] ?? null;
        $user->save();
        // Journalisation du changement de rôle
        \App\Models\SecurityLog::create([
            'user_id' => $user->id,
            'action' => 'role_assigned',
            'ip_address' => request()->ip(),
            'details' => json_encode([
                'assigned_by' => auth()->id(),
                'roles' => $roleNames,
            ]),
        ]);
        return redirect()->route('admin.users.index')->with('success', 'Rôles assignés avec succès.');
    }

    public function assignRolesForm(User $user)
    {
        $roles = Role::all();
        $userRoles = $user->roles()->pluck('roles.id')->toArray();
        return view('admin.users.assign-roles', compact('user', 'roles', 'userRoles'));
    }

    public function createUser()
    {
        $roles = Role::all();
        $groups = \App\Models\Group::all();
        return view('admin.users.create', compact('roles', 'groups'));
    }

    public function storeUser(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'roles' => 'required|array',
            'roles.*' => 'exists:roles,id',
            'groups' => 'nullable|array',
            'groups.*' => 'exists:groups,id',
            'status' => 'required|in:active,inactive',
        ]);
        
        $password = Str::random(10);
        $user = new User();
        $user->name = $request->name;
        $user->surname = $request->surname;
        $user->email = $request->email;
        $user->password = bcrypt($password);
        $user->status = $request->status;
        $user->save();
        
        // Convert role IDs to names
        $roleNames = \Spatie\Permission\Models\Role::whereIn('id', $request->roles)->pluck('name')->toArray();
        // Assigner les rôles
        $user->assignRole($roleNames);
        // Synchroniser utype avec le premier rôle assigné
        $user->utype = $roleNames[0] ?? null;
        $user->save();
        
        // Assign groups if provided
        if ($request->filled('groups')) {
            $user->groups()->attach($request->groups);
        }
        
        // Envoyer un email d'invitation avec le mot de passe temporaire
        Mail::send('emails.user-invitation', [
            'user' => $user,
            'password' => $password,
            'adminName' => auth()->user()->name
        ], function($message) use ($user) {
            $message->to($user->email)
                    ->subject('Invitation à rejoindre SOREMED');
        });
        
        // Log the action
        SecurityLog::create([
            'user_id' => auth()->id(),
            'action' => 'user_created',
            'target_type' => 'user',
            'target_id' => $user->id,
            'details' => 'User created with roles: ' . implode(', ', $roleNames) . ' and groups: ' . implode(', ', $request->groups ?? []),
            'ip_address' => request()->ip(),
        ]);
        
        return redirect()->route('admin.users.index')->with('success', 'Utilisateur créé avec succès. Un email d\'invitation a été envoyé.');
    }

    public function resetUserPassword(User $user)
    {
        $newPassword = Str::random(10);
        $user->password = bcrypt($newPassword);
        $user->save();
        
        // Envoyer un email avec le nouveau mot de passe
        Mail::send('emails.password-reset', [
            'user' => $user,
            'password' => $newPassword,
            'adminName' => auth()->user()->name
        ], function($message) use ($user) {
            $message->to($user->email)
                    ->subject('Réinitialisation de votre mot de passe - SOREMED');
        });
        
        // Log the action
        SecurityLog::create([
            'user_id' => auth()->id(),
            'action' => 'password_reset',
            'target_type' => 'user',
            'target_id' => $user->id,
            'details' => 'Password reset initiated',
            'ip_address' => request()->ip(),
        ]);
        
        return redirect()->route('admin.users.index')->with('success', 'Mot de passe réinitialisé et envoyé par email.');
    }

    public function toggleUserStatus(User $user)
    {
        $oldStatus = $user->status;
        $user->status = $user->status === 'active' ? 'inactive' : 'active';
        $user->save();
        
        // Log the action
        SecurityLog::create([
            'user_id' => auth()->id(),
            'action' => 'status_changed',
            'target_type' => 'user',
            'target_id' => $user->id,
            'details' => "Status changed from {$oldStatus} to {$user->status}",
            'ip_address' => request()->ip(),
        ]);
        
        $status = $user->status === 'active' ? 'activé' : 'désactivé';
        return redirect()->route('admin.users.index')->with('success', "Utilisateur {$status} avec succès.");
    }

    public function deleteUser(User $user)
    {
        // Anonymisation RGPD
        $user->name = 'Utilisateur supprimé';
        $user->email = 'deleted_' . $user->id . '_' . time() . '@deleted.com';
        $user->consent = false;
        $user->consent_accepted_at = null;
        $user->save();
        $user->syncRoles([]);
        if (method_exists($user, 'tokens')) {
            $user->tokens()->delete();
        }
        // Journalisation RGPD
        \App\Models\SecurityLog::create([
            'user_id' => $user->id,
            'action' => 'user_deleted',
            'ip_address' => request()->ip(),
            'details' => json_encode(['deleted_by' => auth()->id()]),
        ]);
        return redirect()->route('admin.users.index')->with('success', 'Utilisateur supprimé avec succès.');
    }

    public function suspendUser(Request $request, User $user)
    {
        $request->validate([
            'suspended_until' => 'required|date|after:now',
        ]);
        $user->suspended_until = $request->suspended_until;
        $user->status = 'inactive';
        $user->save();
        
        // Log the action
        SecurityLog::create([
            'user_id' => auth()->id(),
            'action' => 'user_suspended',
            'target_type' => 'user',
            'target_id' => $user->id,
            'details' => "User suspended until {$user->suspended_until}",
            'ip_address' => request()->ip(),
        ]);
        
        return redirect()->route('admin.users.index')->with('success', 'Utilisateur suspendu jusqu\'à ' . $user->suspended_until->format('d/m/Y H:i'));
    }

    public function updateUserRole(Request $request, User $user)
    {
        $request->validate([
            'utype' => 'required|in:ADM,USR',
        ]);
        $oldType = $user->utype;
        $user->utype = $request->utype;
        $user->save();
        // Log the action
        SecurityLog::create([
            'user_id' => auth()->id(),
            'action' => 'user_type_changed',
            'target_type' => 'user',
            'target_id' => $user->id,
            'details' => "User type changed from {$oldType} to {$user->utype}",
            'ip_address' => request()->ip(),
        ]);
        return redirect()->route('admin.users.index')->with('success', 'Type d\'utilisateur mis à jour avec succès.');
    }

    public function roleMatrix()
    {
        $roles = Role::with('permissions')->get();
        $permissions = Permission::all();
        
        return view('admin.role-matrix', compact('roles', 'permissions'));
    }

    public function securityLogs()
    {
        $logs = SecurityLog::with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(50);
        
        return view('admin.security-logs', compact('logs'));
    }

    public function advancedLogs(Request $request)
    {
        // Vérification des permissions super admin
        if (!auth()->user()->hasPermissionTo('super admin')) {
            abort(403, 'Accès réservé aux super administrateurs.');
        }

        $query = SecurityLog::with('user');

        // Filtres
        if ($request->filled('action_type')) {
            $query->where('action', $request->action_type);
        }

        if ($request->filled('ip_address')) {
            $query->where('ip_address', 'like', '%' . $request->ip_address . '%');
        }

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('date_from')) {
            $query->where('created_at', '>=', $request->date_from . ' 00:00:00');
        }

        if ($request->filled('date_to')) {
            $query->where('created_at', '<=', $request->date_to . ' 23:59:59');
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('description', 'like', '%' . $search . '%')
                  ->orWhere('action', 'like', '%' . $search . '%')
                  ->orWhere('ip_address', 'like', '%' . $search . '%')
                  ->orWhereHas('user', function($userQuery) use ($search) {
                      $userQuery->where('name', 'like', '%' . $search . '%')
                               ->orWhere('email', 'like', '%' . $search . '%');
                  });
            });
        }

        // Tri
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $logs = $query->paginate(100);

        // Statistiques
        $stats = [
            'total_logs' => SecurityLog::count(),
            'today_logs' => SecurityLog::whereDate('created_at', today())->count(),
            'failed_logins' => SecurityLog::where('action', 'failed_login')->count(),
            'suspicious_ips' => SecurityLog::select('ip_address')
                ->where('action', 'failed_login')
                ->groupBy('ip_address')
                ->havingRaw('COUNT(*) > 5')
                ->count(),
        ];

        // Types d'actions pour le filtre
        $actionTypes = SecurityLog::select('action')
            ->distinct()
            ->pluck('action')
            ->sort()
            ->values();

        // Utilisateurs pour le filtre
        $users = User::select('id', 'name', 'email')
            ->orderBy('name')
            ->get();

        // Export
        if ($request->has('export')) {
            return $this->exportLogs($query->get(), $request->export);
        }

        return view('admin.advanced-logs', compact('logs', 'stats', 'actionTypes', 'users'));
    }

    private function exportLogs($logs, $format)
    {
        $filename = 'security_logs_' . date('Y-m-d_H-i-s');

        if ($format === 'excel') {
            $filename .= '.xlsx';
            return $this->exportLogsToExcel($logs, $filename);
        } else {
            $filename .= '.pdf';
            return $this->exportLogsToPdf($logs, $filename);
        }
    }

    private function exportLogsToExcel($logs, $filename)
    {
        $headers = [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($logs) {
            $file = fopen('php://output', 'w');
            
            // Headers
            fputcsv($file, [
                'ID', 'Date', 'Action', 'Description', 'Utilisateur', 'IP', 'User Agent', 'Détails'
            ]);
            
            // Data
            foreach ($logs as $log) {
                fputcsv($file, [
                    $log->id,
                    $log->created_at->format('Y-m-d H:i:s'),
                    $log->action,
                    $log->description,
                    $log->user ? $log->user->name . ' (' . $log->user->email . ')' : 'N/A',
                    $log->ip_address,
                    $log->user_agent,
                    is_array($log->details) ? json_encode($log->details) : $log->details
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }

    private function exportLogsToPdf($logs, $filename)
    {
        // Pour l'instant, on retourne un CSV car PDF nécessite une librairie
        // Vous pouvez installer DomPDF ou Snappy pour les PDFs
        return $this->exportLogsToExcel($logs, str_replace('.pdf', '.csv', $filename));
    }

    public function logDetails(SecurityLog $log)
    {
        // Vérification des permissions super admin
        if (!auth()->user()->hasPermissionTo('super admin')) {
            abort(403, 'Accès réservé aux super administrateurs.');
        }

        return view('admin.log-details', compact('log'));
    }

    public function suspiciousActivity()
    {
        // Vérification des permissions super admin
        if (!auth()->user()->hasPermissionTo('super admin')) {
            abort(403, 'Accès réservé aux super administrateurs.');
        }

        // IPs avec trop de tentatives échouées
        $suspiciousIPs = SecurityLog::select('ip_address')
            ->where('action', 'failed_login')
            ->groupBy('ip_address')
            ->havingRaw('COUNT(*) > 5')
            ->withCount(['logs' => function($query) {
                $query->where('action', 'failed_login');
            }])
            ->get();

        // Actions suspectes
        $suspiciousActions = SecurityLog::whereIn('action', [
            'failed_login', 'unauthorized_access', 'suspicious_activity'
        ])
        ->with('user')
        ->orderBy('created_at', 'desc')
        ->limit(50)
        ->get();

        // Statistiques de sécurité
        $securityStats = [
            'total_suspicious_ips' => $suspiciousIPs->count(),
            'failed_logins_today' => SecurityLog::where('action', 'failed_login')
                ->whereDate('created_at', today())->count(),
            'unauthorized_access_today' => SecurityLog::where('action', 'unauthorized_access')
                ->whereDate('created_at', today())->count(),
        ];

        return view('admin.suspicious-activity', compact('suspiciousIPs', 'suspiciousActions', 'securityStats'));
    }

    private function getActionBadgeClass($action)
    {
        $classes = [
            'login' => 'bg-success',
            'logout' => 'bg-secondary',
            'failed_login' => 'bg-danger',
            'unauthorized_access' => 'bg-warning',
            'suspicious_activity' => 'bg-danger',
            'user_created' => 'bg-info',
            'user_updated' => 'bg-primary',
            'user_deleted' => 'bg-danger',
            'role_assigned' => 'bg-success',
            'role_removed' => 'bg-warning',
            'group_created' => 'bg-info',
            'group_updated' => 'bg-primary',
            'group_deleted' => 'bg-danger',
            'password_changed' => 'bg-warning',
            'mfa_enabled' => 'bg-success',
            'mfa_disabled' => 'bg-warning',
            'session_terminated' => 'bg-danger',
            'ip_whitelisted' => 'bg-success',
            'ip_removed_from_whitelist' => 'bg-warning',
            'security_notification_sent' => 'bg-info',
            'users_imported' => 'bg-success',
            'users_exported' => 'bg-info',
            'export_data' => 'bg-info',
            'permanent_delete' => 'bg-danger',
        ];

        return $classes[$action] ?? 'bg-secondary';
    }

    public function securityChecklist()
    {
        $checklist = [
            'password_policy' => [
                'title' => 'Politique de mots de passe',
                'status' => 'warning',
                'description' => 'Implémenter une politique de mots de passe forts',
                'recommendations' => [
                    'Minimum 8 caractères',
                    'Au moins une majuscule et une minuscule',
                    'Au moins un chiffre et un caractère spécial'
                ]
            ],
            'mfa' => [
                'title' => 'Authentification à deux facteurs',
                'status' => 'danger',
                'description' => 'MFA non activé',
                'recommendations' => [
                    'Activer MFA pour les comptes administrateurs',
                    'Utiliser Google Authenticator ou SMS'
                ]
            ],
            'session_timeout' => [
                'title' => 'Expiration des sessions',
                'status' => 'success',
                'description' => 'Sessions configurées',
                'recommendations' => []
            ],
            'audit_logging' => [
                'title' => 'Journalisation d\'audit',
                'status' => 'success',
                'description' => 'Logs d\'audit activés',
                'recommendations' => []
            ],
            'gdpr_compliance' => [
                'title' => 'Conformité RGPD',
                'status' => 'success',
                'description' => 'Anonymisation des données supprimées',
                'recommendations' => []
            ]
        ];
        
        return view('admin.security-checklist', compact('checklist'));
    }

    public function editUser(User $user)
    {
        $roles = Role::all();
        $groups = \App\Models\Group::all();
        $userRoles = $user->roles()->pluck('roles.id')->toArray();
        $userGroups = $user->groups()->pluck('groups.id')->toArray();
        return view('admin.users.edit', compact('user', 'roles', 'groups', 'userRoles', 'userGroups'));
    }

    public function updateUser(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'roles' => 'required|array',
            'roles.*' => 'exists:roles,id',
            'groups' => 'nullable|array',
            'groups.*' => 'exists:groups,id',
            'status' => 'required|in:active,inactive',
        ]);
        
        $oldData = [
            'name' => $user->name,
            'surname' => $user->surname,
            'email' => $user->email,
            'status' => $user->status,
            'roles' => $user->roles()->pluck('name')->toArray(),
            'groups' => $user->groups()->pluck('name')->toArray(),
        ];
        
        $user->name = $request->name;
        $user->surname = $request->surname;
        $user->email = $request->email;
        $user->status = $request->status;
        $user->save();
        
        // Convert role IDs to names and sync
        $roleNames = \Spatie\Permission\Models\Role::whereIn('id', $request->roles)->pluck('name')->toArray();
        $user->syncRoles($roleNames);
        // Synchroniser utype avec le premier rôle assigné
        $user->utype = $roleNames[0] ?? null;
        $user->save();
        
        // Sync groups
        $user->groups()->sync($request->groups ?? []);
        
        // Log the action
        SecurityLog::create([
            'user_id' => auth()->id(),
            'action' => 'user_updated',
            'target_type' => 'user',
            'target_id' => $user->id,
            'details' => 'User updated: ' . json_encode($oldData) . ' -> ' . json_encode([
                'name' => $user->name,
                'surname' => $user->surname,
                'email' => $user->email,
                'status' => $user->status,
                'roles' => $roleNames,
                'groups' => $user->groups()->pluck('name')->toArray(),
            ]),
            'ip_address' => request()->ip(),
        ]);
        
        return redirect()->route('admin.users.index')->with('success', 'Utilisateur mis à jour avec succès.');
    }

    public function showUser(User $user)
    {
        $logs = SecurityLog::where('target_type', 'user')
                          ->where('target_id', $user->id)
                          ->orWhere('user_id', $user->id)
                          ->orderBy('created_at', 'asc')
                          ->paginate(10);
        
        return view('admin.users.show', compact('user', 'logs'));
    }

    public function exportUserData(User $user)
    {
        // GDPR compliance - export all user data
        $userData = [
            'personal_info' => [
                'name' => $user->name,
                'surname' => $user->surname,
                'email' => $user->email,
                'status' => $user->status,
                'created_at' => $user->created_at->format('Y-m-d H:i:s'),
                'updated_at' => $user->updated_at->format('Y-m-d H:i:s'),
            ],
            'roles' => $user->roles()->pluck('name')->toArray(),
            'groups' => $user->groups()->pluck('name')->toArray(),
            'permissions' => $user->getAllPermissions()->pluck('name')->toArray(),
            'activity_logs' => SecurityLog::where('target_type', 'user')
                                        ->where('target_id', $user->id)
                                        ->orWhere('user_id', $user->id)
                                        ->get()
                                        ->map(function($log) {
                                            return [
                                                'action' => $log->action,
                                                'details' => $log->details,
                                                'ip_address' => $log->ip_address,
                                                'created_at' => $log->created_at->format('Y-m-d H:i:s'),
                                            ];
                                        })->toArray(),
        ];
        
        // Log the export action
        SecurityLog::create([
            'user_id' => auth()->id(),
            'action' => 'data_export',
            'target_type' => 'user',
            'target_id' => $user->id,
            'details' => 'User data exported for GDPR compliance',
            'ip_address' => request()->ip(),
        ]);
        
        return response()->json($userData)
                        ->header('Content-Disposition', 'attachment; filename="user_data_' . $user->id . '.json"');
    }

    public function permanentlyDeleteUser(User $user)
    {
        // GDPR right to be forgotten - permanent deletion
        $userData = [
            'name' => $user->name,
            'surname' => $user->surname,
            'email' => $user->email,
        ];
        
        // Remove all relationships
        $user->syncRoles([]);
        $user->groups()->detach();
        
        // Delete the user permanently
        $user->delete();
        
        // Log the permanent deletion
        SecurityLog::create([
            'user_id' => auth()->id(),
            'action' => 'user_permanently_deleted',
            'target_type' => 'user',
            'target_id' => $user->id,
            'details' => 'User permanently deleted for GDPR compliance: ' . json_encode($userData),
            'ip_address' => request()->ip(),
        ]);
        
        return redirect()->route('admin.users.index')->with('success', 'Utilisateur supprimé définitivement (conformité RGPD).');
    }

    public function enableMFA(User $user)
    {
        // Generate MFA secret
        $secret = \PragmaRX\Google2FA\Google2FA::generateSecretKey();
        $user->mfa_secret = $secret;
        $user->mfa_enabled = true;
        $user->save();
        
        // Log MFA activation
        SecurityLog::create([
            'user_id' => auth()->id(),
            'action' => 'mfa_enabled',
            'target_type' => 'user',
            'target_id' => $user->id,
            'details' => 'MFA enabled for user',
            'ip_address' => request()->ip(),
        ]);
        
        return redirect()->route('admin.users.show', $user)->with('success', 'MFA activé pour l\'utilisateur.');
    }

    public function disableMFA(User $user)
    {
        $user->mfa_secret = null;
        $user->mfa_enabled = false;
        $user->save();
        
        // Log MFA deactivation
        SecurityLog::create([
            'user_id' => auth()->id(),
            'action' => 'mfa_disabled',
            'target_type' => 'user',
            'target_id' => $user->id,
            'details' => 'MFA disabled for user',
            'ip_address' => request()->ip(),
        ]);
        
        return redirect()->route('admin.users.show', $user)->with('success', 'MFA désactivé pour l\'utilisateur.');
    }

    public function forcePasswordChange(User $user)
    {
        $user->password_changed_at = null;
        $user->force_password_change = true;
        $user->save();
        
        // Log forced password change
        SecurityLog::create([
            'user_id' => auth()->id(),
            'action' => 'force_password_change',
            'target_type' => 'user',
            'target_id' => $user->id,
            'details' => 'User forced to change password on next login',
            'ip_address' => request()->ip(),
        ]);
        
        return redirect()->route('admin.users.show', $user)->with('success', 'L\'utilisateur devra changer son mot de passe lors de sa prochaine connexion.');
    }

    public function securityDashboard()
    {
        $stats = [
            'total_users' => User::count(),
            'active_users' => User::where('status', 'active')->count(),
            'mfa_enabled_users' => User::where('mfa_enabled', true)->count(),
            'suspended_users' => User::where('status', 'inactive')->count(),
            'recent_logins' => SecurityLog::where('action', 'login')
                                        ->where('created_at', '>=', now()->subDays(7))
                                        ->count(),
            'failed_logins' => SecurityLog::where('action', 'login_failed')
                                        ->where('created_at', '>=', now()->subDays(7))
                                        ->count(),
            'security_events' => SecurityLog::where('created_at', '>=', now()->subDays(7))
                                          ->count(),
        ];
        
        $recentSecurityEvents = SecurityLog::where('created_at', '>=', now()->subDays(7))
                                          ->orderBy('created_at', 'desc')
                                          ->take(10)
                                          ->get();
        
        $usersWithOldPasswords = User::where('password_changed_at', '<=', now()->subMonths(3))
                                    ->orWhereNull('password_changed_at')
                                    ->get();
        
        return view('admin.security-dashboard', compact('stats', 'recentSecurityEvents', 'usersWithOldPasswords'));
    }

    public function importUsers(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt|max:2048',
        ]);
        
        $file = $request->file('csv_file');
        $path = $file->getRealPath();
        $data = array_map('str_getcsv', file($path));
        
        $headers = array_shift($data);
        $imported = 0;
        $errors = [];
        
        foreach ($data as $row) {
            $userData = array_combine($headers, $row);
            
            try {
                // Validate required fields
                if (empty($userData['email']) || empty($userData['name'])) {
                    $errors[] = "Ligne " . ($imported + 1) . ": Email et nom requis";
                    continue;
                }
                
                // Check if user exists
                if (User::where('email', $userData['email'])->exists()) {
                    $errors[] = "Ligne " . ($imported + 1) . ": Email déjà utilisé";
                    continue;
                }
                
                // Create user
                $password = Str::random(10);
                $user = new User();
                $user->name = $userData['name'];
                $user->surname = $userData['surname'] ?? '';
                $user->email = $userData['email'];
                $user->password = bcrypt($password);
                $user->status = $userData['status'] ?? 'active';
                $user->save();
                
                // Assign roles if specified
                if (!empty($userData['roles'])) {
                    $roleNames = explode(',', $userData['roles']);
                    $user->assignRole($roleNames);
                }
                // Synchroniser utype avec le premier rôle assigné
                $user->utype = $roleNames[0] ?? null;
                $user->save();
                
                // Send invitation email
                Mail::send('emails.user-invitation', [
                    'user' => $user,
                    'password' => $password,
                    'adminName' => auth()->user()->name
                ], function($message) use ($user) {
                    $message->to($user->email)
                            ->subject('Invitation à rejoindre SOREMED');
                });
                
                $imported++;
                
            } catch (\Exception $e) {
                $errors[] = "Ligne " . ($imported + 1) . ": " . $e->getMessage();
            }
        }
        
        // Log the import action
        SecurityLog::create([
            'user_id' => auth()->id(),
            'action' => 'users_imported',
            'target_type' => 'system',
            'target_id' => 0,
            'details' => "Imported {$imported} users from CSV. Errors: " . count($errors),
            'ip_address' => request()->ip(),
        ]);
        
        $message = "Import terminé : {$imported} utilisateurs créés.";
        if (count($errors) > 0) {
            $message .= " Erreurs : " . implode(', ', $errors);
        }
        
        return redirect()->route('admin.users.index')->with('success', $message);
    }

    public function exportUsers(Request $request)
    {
        $users = User::with(['roles', 'groups'])->get();
        
        $filename = 'users_export_' . date('Y-m-d_H-i-s') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];
        
        $callback = function() use ($users) {
            $file = fopen('php://output', 'w');
            
            // Headers
            fputcsv($file, [
                'ID', 'Nom', 'Prénom', 'Email', 'Statut', 'Type', 
                'Rôles', 'Groupes', 'Date création', 'Dernière connexion'
            ]);
            
            // Data
            foreach ($users as $user) {
                fputcsv($file, [
                    $user->id,
                    $user->name,
                    $user->surname,
                    $user->email,
                    $user->status,
                    $user->utype,
                    $user->roles->pluck('name')->implode(', '),
                    $user->groups->pluck('name')->implode(', '),
                    $user->created_at->format('Y-m-d H:i:s'),
                    $user->last_login_at ? $user->last_login_at->format('Y-m-d H:i:s') : 'Jamais'
                ]);
            }
            
            fclose($file);
        };
        
        // Log the export action
        SecurityLog::create([
            'user_id' => auth()->id(),
            'action' => 'users_exported',
            'target_type' => 'system',
            'target_id' => 0,
            'details' => "Exported " . $users->count() . " users to CSV",
            'ip_address' => request()->ip(),
        ]);
        
        return response()->stream($callback, 200, $headers);
    }

    public function sendSecurityNotification(Request $request)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
            'recipients' => 'required|array',
            'recipients.*' => 'in:all,active,admins,selected',
            'selected_users' => 'nullable|array',
            'selected_users.*' => 'exists:users,id',
        ]);
        
        $recipients = collect();
        
        if (in_array('all', $request->recipients)) {
            $recipients = User::where('status', 'active')->get();
        } elseif (in_array('active', $request->recipients)) {
            $recipients = User::where('status', 'active')->get();
        } elseif (in_array('admins', $request->recipients)) {
            $recipients = User::where('utype', 'ADM')->where('status', 'active')->get();
        } elseif (in_array('selected', $request->recipients) && $request->filled('selected_users')) {
            $recipients = User::whereIn('id', $request->selected_users)->get();
        }
        
        $sent = 0;
        foreach ($recipients as $user) {
            try {
                Mail::send('emails.security-notification', [
                    'user' => $user,
                    'subject' => $request->subject,
                    'message' => $request->message,
                    'adminName' => auth()->user()->name
                ], function($message) use ($user, $request) {
                    $message->to($user->email)
                            ->subject('[SÉCURITÉ] ' . $request->subject);
                });
                $sent++;
            } catch (\Exception $e) {
                // Log email sending error
            }
        }
        
        // Log the notification action
        SecurityLog::create([
            'user_id' => auth()->id(),
            'action' => 'security_notification_sent',
            'target_type' => 'system',
            'target_id' => 0,
            'details' => "Security notification sent to {$sent} users: {$request->subject}",
            'ip_address' => request()->ip(),
        ]);
        
        return redirect()->back()->with('success', "Notification de sécurité envoyée à {$sent} utilisateurs.");
    }

    public function sessionManagement()
    {
        $activeSessions = \DB::table('sessions')
                            ->where('last_activity', '>=', now()->subHours(24))
                            ->orderBy('last_activity', 'desc')
                            ->get();
        
        $suspiciousSessions = collect();
        foreach ($activeSessions as $session) {
            $payload = unserialize(base64_decode($session->payload));
            if (isset($payload['_token'])) {
                // Check for suspicious patterns (multiple sessions from same IP, etc.)
                $suspiciousSessions->push($session);
            }
        }
        
        return view('admin.session-management', compact('activeSessions', 'suspiciousSessions'));
    }

    public function terminateSession(Request $request)
    {
        $request->validate([
            'session_id' => 'required|string',
        ]);
        
        \DB::table('sessions')->where('id', $request->session_id)->delete();
        
        SecurityLog::create([
            'user_id' => auth()->id(),
            'action' => 'session_terminated',
            'target_type' => 'session',
            'target_id' => 0,
            'details' => "Session terminated: {$request->session_id}",
            'ip_address' => request()->ip(),
        ]);
        
        return redirect()->back()->with('success', 'Session terminée avec succès.');
    }

    public function ipWhitelist()
    {
        $whitelistedIPs = \App\Models\IPWhitelist::all();
        return view('admin.ip-whitelist', compact('whitelistedIPs'));
    }

    public function addIPToWhitelist(Request $request)
    {
        $request->validate([
            'ip_address' => 'required|ip',
            'description' => 'nullable|string|max:255',
        ]);
        
        \App\Models\IPWhitelist::create([
            'ip_address' => $request->ip_address,
            'description' => $request->description,
            'added_by' => auth()->id(),
        ]);
        
        SecurityLog::create([
            'user_id' => auth()->id(),
            'action' => 'ip_whitelisted',
            'target_type' => 'ip',
            'target_id' => 0,
            'details' => "IP whitelisted: {$request->ip_address}",
            'ip_address' => request()->ip(),
        ]);
        
        return redirect()->back()->with('success', 'IP ajoutée à la liste blanche.');
    }

    public function removeIPFromWhitelist(\App\Models\IPWhitelist $ip)
    {
        $ipAddress = $ip->ip_address;
        $ip->delete();
        
        SecurityLog::create([
            'user_id' => auth()->id(),
            'action' => 'ip_removed_from_whitelist',
            'target_type' => 'ip',
            'target_id' => 0,
            'details' => "IP removed from whitelist: {$ipAddress}",
            'ip_address' => request()->ip(),
        ]);
        
        return redirect()->back()->with('success', 'IP retirée de la liste blanche.');
    }

    // NEWS CRUD
    public function newsIndex() {
        $news = \App\Models\News::orderBy('created_at', 'desc')->paginate(10);
        return view('admin.news.index', compact('news'));
    }
    public function newsCreate() {
        return view('admin.news.create');
    }
    public function newsStore(\Illuminate\Http\Request $request) {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'published_at' => 'nullable|date',
        ]);
        \App\Models\News::create([
            'title' => $request->title,
            'content' => $request->content,
            'published_at' => $request->published_at ?? now(),
        ]);
        return redirect()->route('admin.news.index')->with('success', 'News created successfully.');
    }
    public function newsShow(\App\Models\News $news) {
        return view('admin.news.show', compact('news'));
    }
    public function newsEdit(\App\Models\News $news) {
        return view('admin.news.edit', compact('news'));
    }
    public function newsUpdate(\Illuminate\Http\Request $request, \App\Models\News $news) {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'published_at' => 'nullable|date',
        ]);
        $news->update([
            'title' => $request->title,
            'content' => $request->content,
            'published_at' => $request->published_at,
        ]);
        return redirect()->route('admin.news.index')->with('success', 'News updated successfully.');
    }
    public function newsDestroy(\App\Models\News $news) {
        $news->delete();
        return redirect()->route('admin.news.index')->with('success', 'News deleted successfully.');
    }
    // SERVICES CRUD
    public function servicesIndex() {
        $services = \App\Models\Service::orderBy('created_at', 'desc')->paginate(10);
        return view('admin.services.index', compact('services'));
    }
    public function servicesCreate() {
        return view('admin.services.create');
    }
    public function servicesStore(\Illuminate\Http\Request $request) {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'type' => 'required|string',
            'area' => 'nullable|string',
        ]);
        \App\Models\Service::create($request->only(['name','description','type','area']));
        return redirect()->route('admin.services.index')->with('success', 'Service created successfully.');
    }
    public function servicesShow(\App\Models\Service $service) {
        return view('admin.services.show', compact('service'));
    }
    public function servicesEdit(\App\Models\Service $service) {
        return view('admin.services.edit', compact('service'));
    }
    public function servicesUpdate(\Illuminate\Http\Request $request, \App\Models\Service $service) {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'type' => 'required|string',
            'area' => 'nullable|string',
        ]);
        $service->update($request->only(['name','description','type','area']));
        return redirect()->route('admin.services.index')->with('success', 'Service updated successfully.');
    }
    public function servicesDestroy(\App\Models\Service $service) {
        $service->delete();
        return redirect()->route('admin.services.index')->with('success', 'Service deleted successfully.');
    }
    // CONTACTS CRUD
    public function contactsIndex() {
        $contacts = \App\Models\Contact::orderBy('created_at', 'desc')->paginate(10);
        return view('admin.contacts.index', compact('contacts'));
    }
    public function contactsShow(\App\Models\Contact $contact) {
        return view('admin.contacts.show', compact('contact'));
    }
    public function contactsDestroy(\App\Models\Contact $contact) {
        $contact->delete();
        return redirect()->route('admin.contacts.index')->with('success', 'Contact deleted successfully.');
    }

    // ROLES CRUD
    public function rolesIndex() {
        $roles = \Spatie\Permission\Models\Role::all();
        return view('admin.roles.index', compact('roles'));
    }
    public function rolesCreate() {
        return view('admin.roles.create');
    }
    public function rolesStore(\Illuminate\Http\Request $request) {
        $request->validate(['name' => 'required|string|max:255|unique:roles,name']);
        \Spatie\Permission\Models\Role::create(['name' => $request->name]);
        return redirect()->route('admin.roles.index')->with('success', 'Role created successfully.');
    }
    public function rolesEdit(\Spatie\Permission\Models\Role $role) {
        return view('admin.roles.edit', compact('role'));
    }
    public function rolesUpdate(\Illuminate\Http\Request $request, \Spatie\Permission\Models\Role $role) {
        $request->validate(['name' => 'required|string|max:255|unique:roles,name,'.$role->id]);
        $role->update(['name' => $request->name]);
        return redirect()->route('admin.roles.index')->with('success', 'Role updated successfully.');
    }
    public function rolesDestroy(\Spatie\Permission\Models\Role $role) {
        $role->delete();
        return redirect()->route('admin.roles.index')->with('success', 'Role deleted successfully.');
    }
    // GROUPS CRUD
    public function groupsIndex() {
        $groups = \App\Models\Group::all();
        return view('admin.groups.index', compact('groups'));
    }
    public function groupsCreate() {
        return view('admin.groups.create');
    }
    public function groupsStore(\Illuminate\Http\Request $request) {
        $request->validate(['name' => 'required|string|max:255|unique:groups,name']);
        \App\Models\Group::create(['name' => $request->name]);
        return redirect()->route('admin.groups.index')->with('success', 'Group created successfully.');
    }
    public function groupsEdit(\App\Models\Group $group) {
        return view('admin.groups.edit', compact('group'));
    }
    public function groupsUpdate(\Illuminate\Http\Request $request, \App\Models\Group $group) {
        $request->validate(['name' => 'required|string|max:255|unique:groups,name,'.$group->id]);
        $group->update(['name' => $request->name]);
        return redirect()->route('admin.groups.index')->with('success', 'Group updated successfully.');
    }
    public function groupsDestroy(\App\Models\Group $group) {
        $group->delete();
        return redirect()->route('admin.groups.index')->with('success', 'Group deleted successfully.');
    }
    // USERS CRUD
    public function usersIndex() {
        $users = \App\Models\User::orderBy('created_at', 'desc')->paginate(10);
        return view('admin.users.index', compact('users'));
    }
    public function usersCreate() {
        $roles = \Spatie\Permission\Models\Role::all();
        $groups = \App\Models\Group::all();
        return view('admin.users.create', compact('roles', 'groups'));
    }
    public function usersStore(\Illuminate\Http\Request $request) {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'roles' => 'array',
            'groups' => 'array'
        ]);
        $user = \App\Models\User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);
        if ($request->roles) $user->syncRoles($request->roles);
        // Synchroniser utype avec le premier rôle assigné
        $roleNames = is_array($request->roles) ? $request->roles : [];
        $firstRole = null;
        if (!empty($roleNames)) {
            $firstRoleName = \Spatie\Permission\Models\Role::where('id', $roleNames[0])->value('name');
            $firstRole = $firstRoleName;
        }
        $user->utype = $firstRole;
        $user->save();
        if ($request->groups) $user->groups()->sync($request->groups);
        return redirect()->route('admin.users.index')->with('success', 'User created successfully.');
    }
    public function usersEdit(\App\Models\User $user) {
        $roles = \Spatie\Permission\Models\Role::all();
        $groups = \App\Models\Group::all();
        return view('admin.users.edit', compact('user', 'roles', 'groups'));
    }
    public function usersUpdate(\Illuminate\Http\Request $request, \App\Models\User $user) {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$user->id,
            'roles' => 'array',
            'groups' => 'array'
        ]);
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);
        if ($request->roles) $user->syncRoles($request->roles);
        // Synchroniser utype avec le premier rôle assigné
        $roleNames = is_array($request->roles) ? $request->roles : [];
        $firstRole = null;
        if (!empty($roleNames)) {
            $firstRoleName = \Spatie\Permission\Models\Role::where('id', $roleNames[0])->value('name');
            $firstRole = $firstRoleName;
        }
        $user->utype = $firstRole;
        $user->save();
        if ($request->groups) $user->groups()->sync($request->groups);
        return redirect()->route('admin.users.index')->with('success', 'User updated successfully.');
    }
    public function usersDestroy(\App\Models\User $user) {
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'User deleted successfully.');
    }
}
