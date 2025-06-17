<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\auth\AuthRequest;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Traits\ApiResponse;


class AuthController extends Controller
{
    use ApiResponse;

    public function login(AuthRequest $request)
    {
        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return $this->errorResponse('Your password or email is incorrect', [], 422);
        }

        $token = $user->createToken("auth_token")->plainTextToken;

        $response = [
            "id" => $user->id,
            "name" => $user->name,
            "email" => $user->email,
            "role" => $user->getRoleNames()->first(), // Retorna el nombre del rol
            "permissions" => $user->getAllPermissions()->pluck('name'),
            "token" => $token,
        ];

        return $this->successResponse('Login successful', $response, 200);
    }
}
