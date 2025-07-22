<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RoleAndPermissionSeeder extends Seeder
{
    public function run()
    {
        // 1. Créer toutes les permissions
        $permissions = [
            // User Management
            'view users', 'create users', 'edit users', 'delete users', 'assign roles', 'suspend users', 'activate users',
            // Role Management
            'view roles', 'create roles', 'edit roles', 'delete roles',
            // Group Management
            'view groups', 'create groups', 'edit groups', 'delete groups', 'assign users to groups', 'assign roles to groups',
            // Content Management
            'view news', 'create news', 'edit news', 'delete news', 'publish news',
            'view services', 'create services', 'edit services', 'delete services', 'publish services',
            // Contact Management
            'view contacts', 'reply contacts', 'delete contacts',
            // Security & Compliance
            'view security logs', 'view security checklist', 'view security dashboard', 'manage security', 'export data',
            // Admin Features
            'access admin panel', 'manage system settings',
        ];
        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm]);
        }

        // 2. Créer les rôles
        $roles = [
            'super admin', 'admin', 'manager', 'editor', 'viewer', 'user'
        ];
        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role]);
        }

        // 3. Attribution des permissions par rôle (matrice)
        $rolePermissions = [
            'super admin' => $permissions,
            'admin' => [
                // User Management
                'view users', 'create users', 'edit users', 'assign roles', 'suspend users', 'activate users',
                // Role Management
                'view roles', 'create roles', 'edit roles',
                // Group Management
                'view groups', 'create groups', 'edit groups',  'assign users to groups', 'assign roles to groups',
                // Content Management
                'view news', 'create news', 'edit news', 'delete news', 'publish news',
                'view services', 'create services', 'edit services', 'delete services', 'publish services',
                // Contact Management
                'view contacts', 'reply contacts', 'delete contacts',
                // Security & Compliance
                'view security logs', 'view security checklist', 'view security dashboard', 'manage security', 'export data',
                // Admin Features
                'access admin panel',
            ],
            'manager' => [
                'view users',
                'view groups', 'create groups', 'edit groups', 'assign users to groups',
                'view news', 'create news', 'edit news',
                'view services', 'create services', 'edit services',
                'view contacts', 'reply contacts', 'delete contacts',
                'export data',
            ],
            'editor' => [
                'view news', 'create news', 'edit news', 'delete news', 'publish news',
                'view services', 'create services', 'edit services', 'delete services', 'publish services',
            ],
            'viewer' => [
                'view news', 'view services', 'view contacts',
            ],
            'user' => [
                'view news', 'view services',
            ],
        ];
        foreach ($rolePermissions as $role => $perms) {
            $roleObj = Role::where('name', $role)->first();
            $roleObj->syncPermissions($perms);
        }


        // 5. Créer un utilisateur de test pour chaque rôle
        $demoUsers = [
            ['name' => 'Super Admin', 'email' => 'superadmin@demo.com', 'role' => 'super admin'],
            ['name' => 'Admin', 'email' => 'admin@demo.com', 'role' => 'admin'],
            ['name' => 'Manager', 'email' => 'manager@demo.com', 'role' => 'manager'],
            ['name' => 'Editor', 'email' => 'editor@demo.com', 'role' => 'editor'],
            ['name' => 'Viewer', 'email' => 'viewer@demo.com', 'role' => 'viewer'],
            ['name' => 'User', 'email' => 'user@demo.com', 'role' => 'user'],
        ];
        foreach ($demoUsers as $demo) {
            $user = User::firstOrCreate(
                ['email' => $demo['email']],
                [
                    'name' => $demo['name'],
                    'password' => Hash::make('password'),
                    'utype' => $demo['role'], // Ajout du champ utype
                    'status' => 'active',
                    'consent' => true,
                    'consent_accepted_at' => now(),
                ]
            );
            $user->assignRole($demo['role']);
        }
    }
} 