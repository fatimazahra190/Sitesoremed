<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\GroupUserRole;
use App\Models\SecurityLog;

class GroupController extends Controller
{
    public function index()
    {
        $groups = Group::with('users')->get();
        return view('admin.groups.index', compact('groups'));
    }

    public function create()
    {
        $users = User::where('status', 'active')->orderBy('name')->get();
        return view('admin.groups.create', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:groups,name',
            'description' => 'nullable|string|max:255',
            'users' => 'nullable|array',
            'users.*' => 'exists:users,id',
        ]);
        
        $group = Group::create($request->only('name', 'description'));
        
        // Assign initial users if provided
        if ($request->filled('users')) {
            $group->users()->attach($request->users);
        }
        
        // Log the action
        SecurityLog::create([
            'user_id' => auth()->id(),
            'action' => 'group_created',
            'target_type' => 'group',
            'target_id' => $group->id,
            'details' => "Group '{$group->name}' created with " . count($request->users ?? []) . " initial members",
            'ip_address' => request()->ip(),
        ]);
        
        return redirect()->route('admin.groups.index')->with('success', 'Groupe créé avec succès.');
    }

    public function edit(Group $group)
    {
        $users = User::all();
        $group->load('users');
        $roles = Role::all();
        // Préparer la matrice des rôles par membre
        $userRoles = [];
        foreach ($group->users as $user) {
            $userRoles[$user->id] = GroupUserRole::where('group_id', $group->id)->where('user_id', $user->id)->pluck('role_id')->toArray();
        }
        return view('admin.groups.edit', compact('group', 'users', 'roles', 'userRoles'));
    }

    public function update(Request $request, Group $group)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:255',
        ]);
        $group->update($request->only('name', 'description'));
        return redirect()->route('admin.groups.index')->with('success', 'Groupe mis à jour.');
    }

    public function destroy(Group $group)
    {
        $group->delete();
        return redirect()->route('admin.groups.index')->with('success', 'Groupe supprimé.');
    }

    // Gestion des membres du groupe
    public function updateMembers(Request $request, Group $group)
    {
        $request->validate([
            'members' => 'array',
            'members.*' => 'exists:users,id',
        ]);
        $group->users()->sync($request->members ?? []);
        return redirect()->route('admin.groups.edit', $group)->with('success', 'Membres du groupe mis à jour.');
    }

    // Gestion avancée : affectation de rôles spécifiques par groupe (matrice)
    public function updateRoles(Request $request, Group $group)
    {
        // $request->roles est un tableau [user_id => [role_id, ...], ...]
        $rolesData = $request->input('roles', []);
        // On supprime les anciens rôles pour ce groupe
        GroupUserRole::where('group_id', $group->id)->delete();
        // On ajoute les nouveaux rôles
        foreach ($rolesData as $userId => $roleIds) {
            foreach ($roleIds as $roleId) {
                GroupUserRole::create([
                    'group_id' => $group->id,
                    'user_id' => $userId,
                    'role_id' => $roleId,
                ]);
            }
        }
        return redirect()->route('admin.groups.edit', $group)->with('success', 'Matrice des rôles mise à jour.');
    }

    public function assignRolesForm(Group $group)
    {
        $roles = \App\Models\Role::all();
        $groupRoles = $group->roles()->pluck('roles.id')->toArray();
        return view('admin.groups.assign-roles', compact('group', 'roles', 'groupRoles'));
    }
    
    public function assignRoles(Request $request, Group $group)
    {
        $request->validate([
            'roles' => 'array',
            'roles.*' => 'exists:roles,id'
        ]);
        
        $group->roles()->sync($request->roles ?? []);
        
        return redirect()->route('admin.groups.index')->with('success', 'Rôles assignés au groupe avec succès.');
    }
    
    public function assignUsersForm(Group $group)
    {
        $users = \App\Models\User::where('status', 'active')->get();
        $groupUsers = $group->users()->pluck('users.id')->toArray();
        return view('admin.groups.assign-users', compact('group', 'users', 'groupUsers'));
    }
    
    public function assignUsers(Request $request, Group $group)
    {
        $request->validate([
            'users' => 'array',
            'users.*' => 'exists:users,id'
        ]);
        
        $group->users()->sync($request->users ?? []);
        
        return redirect()->route('admin.groups.index')->with('success', 'Utilisateurs assignés au groupe avec succès.');
    }
} 