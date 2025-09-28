<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class RolePermissionController extends Controller
{
    /**
     * Display a listing of roles.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $roles = Role::with('permissions')->latest()->paginate(10);
        $permissions = Permission::all();
        
        return view('pages.roles', compact('roles', 'permissions'));
    }

    /**
     * Store a newly created role in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:roles,name',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        try {
            DB::beginTransaction();
            
            $role = Role::create(['name' => $validated['name']]);
            
            if (isset($validated['permissions'])) {
                $permissions = Permission::whereIn('id', $validated['permissions'])->pluck('name');
                $role->syncPermissions($permissions);
            }
            
            DB::commit();
            
            return redirect()
                ->route('admin.Roles')
                ->with('success', 'Role created successfully.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to create role: ' . $e->getMessage());
        }
    }

    /**
     * Update the specified role in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $role = Role::findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $role->id,
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        try {
            DB::beginTransaction();
            
            $role->update(['name' => $validated['name']]);
            
            if (isset($validated['permissions'])) {
                $permissions = Permission::whereIn('id', $validated['permissions'])->pluck('name');
                $role->syncPermissions($permissions);
            } else {
                $role->syncPermissions([]);
            }
            
            DB::commit();
            
            return redirect()
                ->route('admin.Roles')
                ->with('success', 'Role updated successfully.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to update role: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified role from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $role = Role::findOrFail($id);
        
        if ($role->name === 'super-admin') {
            return back()->with('error', 'Cannot delete the super-admin role.');
        }
        
        try {
            DB::beginTransaction();
            
            $role->syncPermissions([]);

            $role->delete();
            
            DB::commit();
            
            return redirect()
                ->route('admin.Roles');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to delete role: ' . $e->getMessage());
        }
    }


    /**
     * Display a listing of permissions.
     *
     * @return \Illuminate\Http\Response
     */
    public function permissionsIndex()
    {
        $permissions = Permission::latest()->paginate(10);
        return view('pages.permissions', compact('permissions'));
    }

    /**
     * Store a newly created permission in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function permissionStore(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:permissions,name',
        ]);

        try {
            Permission::create([
                'name' => $validated['name'],
                'guard_name' => 'admin',
            ]);

            return redirect()
                ->route('admin.Permissions')
                ->with('success', 'Permission created successfully.');
                
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Failed to create permission: ' . $e->getMessage());
        }
    }

    /**
     * Update the specified permission in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function permissionUpdate(Request $request, $id)
    {
        $permission = Permission::findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:permissions,name,' . $permission->id,
        ]);

        try {
            $permission->update([
                'name' => $validated['name']
            ]);

            return redirect()
                ->route('admin.Permissions')
                ->with('success', 'Permission updated successfully.');
                
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Failed to update permission: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified permission from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function permissionDestroy($id)
    {
        $permission = Permission::findOrFail($id);
        
        try {
            // Check if permission is being used by any role
            if ($permission->roles()->count() > 0) {
                return back()
                    ->with('error', 'Cannot delete permission. It is assigned to one or more roles.');
            }
            
            $permission->delete();
            
            return redirect()
                ->route('admin.Permissions')
                ->with('success', 'Permission deleted successfully.');
                
        } catch (\Exception $e) {
            return back()
                ->with('error', 'Failed to delete permission: ' . $e->getMessage());
        }
    }
}
