<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\borrowing\BorrowBooksRequest;
use App\Http\Requests\borrowing\ReturnBooksRequest;
use App\Models\Book;
use App\Models\Borrowing;
use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class BorrowingController extends Controller
{
    use ApiResponse;

    public function filterBooks(Request $request)
    {
        $user = $request->user();
        $search = $request->input('search');

        // Libros disponibles
        $availableQuery = Book::with('author', 'book_status')
            ->where('book_status_id', 1);

        if ($search) {
            $availableQuery->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('isbn', 'like', "%{$search}%")
                    ->orWhereHas('author', fn($a) => $a->where('name', 'like', "%{$search}%"));
            });
        }

        $availableBooks = $availableQuery->get();

        $toReturnQuery = Borrowing::with(['book.author', 'book.book_status'])
            ->whereNull('returned_at');

        if ($user->hasRole('Client')) {
            $toReturnQuery->where('user_id', $user->id);
        }

        $toReturn = $toReturnQuery->get()->map(fn($b) => $b->book);

        return $this->successResponse("Filtered books", [
            'available' => $availableBooks,
            'to_return' => $toReturn,
            'filter' => $search
        ]);
    }

    public function borrowBooks(BorrowBooksRequest $request, $userId)
    {
        $user = User::findOrFail($userId);
        if (!$user) {
            return $this->errorResponse('User not found.', [], 404);
        }

        $bookIds = $request->book_ids;
        $activeBorrowings = Borrowing::where('user_id', $userId)
            ->whereNull('returned_at')
            ->count();

        if ($activeBorrowings + count($bookIds) > 3) {
            return $this->errorResponse('User cannot borrow more than 3 books simultaneously.', [], 403);
        }

        DB::beginTransaction();
        try {
            foreach ($bookIds as $bookId) {
                $book = Book::findOrFail($bookId);

                if ($book->book_status_id !== 1) {
                    throw new \Exception("Book '{$book->title}' is not available.");
                }

                Borrowing::create([
                    'user_id' => $userId,
                    'book_id' => $bookId,
                    'borrowed_at' => now(),
                    'due_date' => now()->addDays(14),
                ]);

                $book->update(['book_status_id' => 2]);
            }

            DB::commit();
            return $this->successResponse("Books borrowed successfully.");
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error borrowing books', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            return $this->errorResponse("Error borrowing books", [$e->getMessage()], 500);
        }
    }

    public function returnBooks(ReturnBooksRequest $request, $userId)
    {
        $user = User::findOrFail($userId);
        if (!$user) {
            return $this->errorResponse('User not found.', [], 404);
        }
        DB::beginTransaction();
        try {
            foreach ($request->book_ids as $bookId) {
                $borrowing = Borrowing::where('user_id', $userId)
                    ->where('book_id', $bookId)
                    ->whereNull('returned_at')
                    ->first();

                if (!$borrowing) {
                    throw new \Exception("The book with ID {$bookId} is not currently borrowed by this user.");
                }

                $borrowing->update(['returned_at' => now()]);
                Book::where('id', $bookId)->update(['book_status_id' => 1]);
            }

            DB::commit();
            return $this->successResponse("Books returned successfully.");
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error returning books', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            return $this->errorResponse("Error returning books", [$e->getMessage()], 500);
        }
    }

    public function getCurrentBorrower($bookId)
    {
        $borrowing = Borrowing::with('user')
            ->where('book_id', $bookId)
            ->whereNull('returned_at')
            ->first();

        if (!$borrowing) {
            return $this->errorResponse("No active borrower for this book", [], 404);
        }

        return $this->successResponse("Current borrower info", [
            'user' => $borrowing->user,
            'borrowed_at' => $borrowing->borrowed_at,
            'due_date' => $borrowing->due_date,
        ]);
    }
}
