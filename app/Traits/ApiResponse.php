<?php

namespace App\Traits;

trait ApiResponse
{
    public function successResponse($message, $data = [],  $status = 200)
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data,
            'status' => $status,
        ], $status);
    }

    public function errorResponse($message, $data = [], $status = 400)
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'data' => $data,
            'status' => $status,
        ], $status);
    }
}
