<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class RoleController extends Controller
{
    /**
     * Display all roles and permissions.
     */
    public function index()
    {
        $roles = Role::with('permissions')
            ->orderByDesc('id')
            ->get();

        $permissions = Permission::orderBy('name')->get();

        return view('admin.roles.index', compact(
            'roles',
            'permissions'
        ));
    }

    /**
     * Create a new role.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                'unique:roles,name',
            ],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['exists:permissions,name'],
        ]);

        $role = Role::create([
            'name' => $validated['name'],
            'guard_name' => 'web',
        ]);

        $role->syncPermissions($validated['permissions'] ?? []);

        return redirect()
            ->route('admin.roles.index')
            ->with('success', 'Role created successfully!');
    }

    /**
     * Enable or disable a role permission.
     */
    public function togglePermission(Request $request, Role $role)
    {
        abort_if(
            $role->name === 'Super Admin',
            403,
            'Super Admin permissions cannot be changed.'
        );

        $validated = $request->validate([
            'permission' => [
                'required',
                'string',
                'exists:permissions,name',
            ],
            'enabled' => ['required', 'boolean'],
        ]);

        $permission = Permission::findByName(
            $validated['permission'],
            'web'
        );

        if ($validated['enabled']) {
            $role->givePermissionTo($permission);
        } else {
            $role->revokePermissionTo($permission);
        }

        app(PermissionRegistrar::class)->forgetCachedPermissions();

        return response()->json([
            'success' => true,
            'enabled' => (bool) $validated['enabled'],
            'permissions_count' => $role->permissions()->count(),
            'message' => 'Permission updated successfully.',
        ]);
    }

    /**
     * Delete a role.
     */
    public function destroy(Role $role)
    {
        abort_if(
            $role->name === 'Super Admin',
            403,
            'Super Admin role cannot be deleted.'
        );

        $role->delete();

        app(PermissionRegistrar::class)->forgetCachedPermissions();

        return redirect()
            ->route('admin.roles.index')
            ->with('success', 'Role deleted successfully!');
    }
}
