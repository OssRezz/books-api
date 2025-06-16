<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponse;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    use ApiResponse;

    /**
     * List all roles.
     */
    public function index()
    {
        try {
            $roles = Role::select('id', 'name')->get();
            return $this->successResponse('Roles list', $roles);
        } catch (\Exception $e) {
            Log::error('Error listing roles', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            return $this->errorResponse('Error listing roles', [$e->getMessage()], 500);
        }
    }

    /**
     * Show a specific role and its permissions.
     */
    public function show($id)
    {
        try {
            $role = Role::with('permissions')->findOrFail($id);
            return $this->successResponse('Role details', $role);
        } catch (\Exception $e) {
            Log::error('Error showing role', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            return $this->errorResponse('Error showing role', [$e->getMessage()], 500);
        }
    }

    /**
     * Create a new role.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:roles,name',
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,name',
        ]);

        try {
            $role = Role::create(['name' => $request->name]);

            if ($request->has('permissions')) {
                $role->syncPermissions($request->permissions);
            }

            return $this->successResponse('Role created', $role, 201);
        } catch (\Exception $e) {
            Log::error('Error creating role', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            return $this->errorResponse('Error creating role', [$e->getMessage()], 500);
        }
    }

    /**
     * Update an existing role.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'sometimes|required|string|unique:roles,name,' . $id,
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,name',
        ]);

        try {
            $role = Role::findOrFail($id);

            if ($request->has('name')) {
                $role->name = $request->name;
                $role->save();
            }

            if ($request->has('permissions')) {
                $role->syncPermissions($request->permissions);
            }

            return $this->successResponse('Role updated', $role);
        } catch (\Exception $e) {
            Log::error('Error updating role', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            return $this->errorResponse('Error updating role', [$e->getMessage()], 500);
        }
    }

    /**
     * Delete a role.
     */
    public function destroy($id)
    {
        try {
            $role = Role::findOrFail($id);
            $role->delete();

            return $this->successResponse('Role deleted');
        } catch (\Exception $e) {
            Log::error('Error deleting role', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            return $this->errorResponse('Error deleting role', [$e->getMessage()], 500);
        }
    }
}
