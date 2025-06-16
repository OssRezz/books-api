<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\users\StoreUserRequest;
use App\Http\Requests\users\UpdateUserRequest;
use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    use ApiResponse;

    public function index()
    {
        try {
            $users = User::with('roles:id,name')->paginate(10);
            return $this->successResponse('User list', $users);
        } catch (\Exception $e) {
            Log::error('Error listing users', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            return $this->errorResponse('Error listing users', [$e->getMessage()], 500);
        }
    }

    public function store(StoreUserRequest $request)
    {
        try {
            $data = $request->validated();

            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'library_id' => $data['library_id'],
                'password' => Hash::make($data['password']),
            ]);

            $user->assignRole($data['role']);

            return $this->successResponse('User created', $user, 201);
        } catch (\Exception $e) {
            Log::error('Error creating user', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            return $this->errorResponse('Error creating user', [$e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        try {
            $user = User::with('roles')->findOrFail($id);
            return $this->successResponse('User details', $user);
        } catch (\Exception $e) {
            Log::error('Error showing user', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            return $this->errorResponse('Error showing user', [$e->getMessage()], 500);
        }
    }

    public function update(UpdateUserRequest $request, $id)
    {
        try {
            $user = User::findOrFail($id);
            $data = $request->validated();

            $user->update([
                'name' => $data['name'],
                'email' => $data['email'],
                'library_id' => $data['library_id'],
                'password' => isset($data['password']) ? Hash::make($data['password']) : $user->password,
            ]);

            if (isset($data['role'])) {
                $user->syncRoles([$data['role']]);
            }

            return $this->successResponse('User updated', $user);
        } catch (\Exception $e) {
            Log::error('Error updating user', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            return $this->errorResponse('Error updating user', [$e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $user = User::findOrFail($id);
            $user->delete();

            return $this->successResponse('User deleted');
        } catch (\Exception $e) {
            Log::error('Error deleting user', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            return $this->errorResponse('Error deleting user', [$e->getMessage()], 500);
        }
    }

    public function getAllUsers()
    {
        try {
            $users = User::all();
            return $this->successResponse('Users list', $users);
        } catch (\Exception $e) {
            Log::error('Error listing users', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            return $this->errorResponse('Error listing users', [$e->getMessage()], 500);
        }
    }
}
