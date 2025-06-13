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

    /**
     * Login
     * 
     * Autentica un usuario y retorna un token.
     * 
     * @group Auth
     * @bodyParam email string required Email del usuario. Example: admin@example.com
     * @bodyParam password string required Contraseña del usuario. Example: secret123
     * 
     * @response 200 {
     *  "message": "Login successful",
     *  "success": true,
     *  "data": {
     *      "id": 1,
     *      "name": "John",
     *      "last_name": "Doe",
     *      "email": "john@example.com",
     *      "permissions": ["view_users"],
     *      "token": "1|xxx..."
     *  },
     *  "status": 200
     * }
     */
    public function login(AuthRequest $request)
    {
        $user = User::where('email', $request->email)->first();

        if (!Hash::check($request->password, $user->password)) {
            return $this->errorResponse('Contraseña incorrecta', [], 422);
        }

        $token = $user->createToken("auth_token")->plainTextToken;
        $response = [
            "id" => $user->id,
            "name" => $user->name,
            "last_name" => $user->last_name,
            "email" => $user->email,
            "permissions" => $user->getAllPermissions()->pluck('name'),
            "token" => $token,
        ];

        return $this->successResponse('Login exitoso', $response, 200);
    }
}
