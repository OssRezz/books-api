<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Models\BookStatus;
use App\Traits\ApiResponse;
use Illuminate\Support\Facades\Log;

class BookStatusController extends Controller
{
    use ApiResponse;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $book_statuses = BookStatus::all();
            return $this->successResponse('Book statuses list', $book_statuses);
        } catch (\Exception $e) {
            Log::error('Error listing book statuses', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            return $this->errorResponse('Error listing book statuses', [$e->getMessage()], 500);
        }
    }
}
