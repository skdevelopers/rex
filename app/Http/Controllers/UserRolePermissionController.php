<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class UserRolePermissionController extends Controller
{
    public function editRoles(User $user)
    {
        $roles = Role::all();
        return view('users.edit_roles', compact('user', 'roles'));
    }

    public function updateRoles(Request $request, User $user)
    {
        $user->syncRoles($request->roles);
        return redirect()->route('users.index')->with('success', 'User roles updated successfully.');
    }

    public function editPermissions(User $user)
    {
        $permissions = Permission::all();
        return view('users.edit_permissions', compact('user', 'permissions'));
    }

    public function updatePermissions(Request $request, User $user)
    {
        $user->syncPermissions($request->permissions);
        return redirect()->route('users.index')->with('success', 'User permissions updated successfully.');
    }
}
