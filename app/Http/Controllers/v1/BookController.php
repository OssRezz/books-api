<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\books\CreateBookRequest;
use App\Http\Requests\books\UpdateBookRequest;
use App\Models\Book;
use App\Traits\ApiResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class BookController extends Controller
{
    use ApiResponse;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $books = Book::with('author', 'book_status')->orderBy('id', 'desc')->paginate(10);
            return $this->successResponse('Books list', $books);
        } catch (\Exception $e) {
            Log::error('Error listing books', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            return $this->errorResponse('Error listing books', [$e->getMessage()], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateBookRequest $request)
    {
        try {
            $validatedData = $request->validated();

            if ($request->hasFile('image_book')) {
                $bookImage = $request->file('image_book');
                $bookImageName = uniqid('image_') . '.' . $bookImage->getClientOriginalExtension();
                $bookImage->storeAs('books', $bookImageName, 'public');
                $validatedData['image'] = $bookImageName;
            }

            $book = Book::create($validatedData);
            return $this->successResponse("Book created", $book, 201);
        } catch (\Exception $e) {
            Log::error("Error creating book", [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            return $this->errorResponse("Error creating book", [$e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $book = Book::find($id);

            if (!$book) {
                return $this->errorResponse("Book not found", [], 404);
            }

            return $this->successResponse("Book details", $book);
        } catch (\Exception $e) {
            Log::error("Error getting book details", [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            return $this->errorResponse("Error getting book details", [$e->getMessage()]);
        }
    }

    /**
     * Update the specified resource in storage.
     */

    public function update(UpdateBookRequest $request, string $id)
    {
        try {
            $book = Book::find($id);

            if (!$book) {
                return $this->errorResponse("Book not found", [], 404);
            }

            $validatedData = $request->validated();

            if ($request->hasFile('image_book')) {
                // Eliminar imagen anterior si existe
                if ($book->image && Storage::disk('public')->exists('books/' . $book->image)) {
                    Storage::disk('public')->delete('books/' . $book->image);
                }

                // Guardar la nueva imagen
                $bookImage = $request->file('image_book');
                $bookImageName = uniqid('image_') . '.' . $bookImage->getClientOriginalExtension();
                $bookImage->storeAs('books', $bookImageName, 'public');
                $validatedData['image'] = $bookImageName;
            }

            $book->update($validatedData);

            return $this->successResponse("Book updated", $book);
        } catch (\Exception $e) {
            Log::error("Error updating book", [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            return $this->errorResponse("Error updating book", [$e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $book = Book::find($id);

            if (!$book) {
                return $this->errorResponse("Book not found", [], 404);
            }
            
            $book->delete();
            return $this->successResponse("Book deleted", [], 204);
        } catch (\Exception $e) {
            Log::error("Error deleting book", [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            return $this->errorResponse("Error deleting book", [$e->getMessage()], 500);
        }
    }
}
