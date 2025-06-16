<?php

use App\Http\Controllers\v1\AuthController;
use App\Http\Controllers\v1\AuthorController;
use App\Http\Controllers\v1\BookController;
use App\Http\Controllers\v1\BookStatusController;
use App\Http\Controllers\v1\BorrowingController;
use App\Http\Controllers\v1\RoleController;
use App\Http\Controllers\v1\UserController;
use Illuminate\Support\Facades\Route;

Route::post('v1/login', [AuthController::class, 'login'])->name('auth.login');


Route::group(['middleware' => ['auth:sanctum']], function () {

    Route::prefix('v1')->group(function () {

        Route::apiResource('authors', AuthorController::class)->only(['index']);
        Route::apiResource('book-statuses', BookStatusController::class)->only(['index']);
        Route::apiResource('books', BookController::class);

        Route::apiResource('users', UserController::class);
        Route::get('users-all', [UserController::class, 'getAllUsers']);

        Route::apiResource('roles', RoleController::class);
        Route::get('borrowings/books/filter', [BorrowingController::class, 'filterBooks']);

        Route::prefix('users/{userId}/borrowings')->group(function () {
            Route::post('/borrow', [BorrowingController::class, 'borrowBooks']);
            Route::post('/return', [BorrowingController::class, 'returnBooks']);
        });
        Route::get('borrowings/book/{bookId}/current-borrower', [BorrowingController::class, 'getCurrentBorrower']);
    });
});
