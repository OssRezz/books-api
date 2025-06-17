<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\BookStatus;
use App\Models\Borrowing;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Author;

class BorrowingTest extends TestCase
{
    use RefreshDatabase;


    public function setUp(): void
    {
        parent::setUp();

        BookStatus::factory()->create(['id' => 1, 'name' => 'Available']);
        BookStatus::factory()->create(['id' => 2, 'name' => 'Borrowed']);

        Author::factory()->create(['id' => 1]);
    }
    protected function authenticate()
    {
        $user = User::factory()->create([
            'password' => bcrypt('password123'),
            'library_id' => '1234567890',
        ]);

        $token = $this->postJson('/api/v1/login', [
            'email' => $user->email,
            'password' => 'password123',
        ])->json('data.token');

        return [$user, [
            'Authorization' => "Bearer $token",
            'Accept' => 'application/json',
        ]];
    }

    public function test_user_can_borrow_books()
    {
        [$user, $headers] = $this->authenticate();

        $books = Book::factory()->count(2)->create([
            'book_status_id' => 1,
        ]);

        $bookIds = $books->pluck('id')->toArray();

        $response = $this->postJson("/api/v1/users/{$user->id}/borrowings/borrow", [
            'book_ids' => $bookIds,
        ], $headers);

        $response->assertStatus(200)
            ->assertJson(['success' => true]);

        $this->assertDatabaseHas('borrowings', [
            'user_id' => $user->id,
            'book_id' => $bookIds[0],
        ]);

        $this->assertDatabaseHas('books', [
            'id' => $bookIds[0],
            'book_status_id' => 2,
        ]);
    }

    public function test_user_cannot_borrow_more_than_3_books()
    {
        [$user, $headers] = $this->authenticate();

        Book::factory()->count(3)->create([
            'book_status_id' => 2, // ya prestados
        ]);

        Borrowing::factory()->count(3)->create([
            'user_id' => $user->id,
            'returned_at' => null,
        ]);

        $newBooks = Book::factory()->count(1)->create([
            'book_status_id' => 1,
        ]);

        $response = $this->postJson("/api/v1/users/{$user->id}/borrowings/borrow", [
            'book_ids' => [$newBooks->first()->id],
        ], $headers);

        $response->assertStatus(403)
            ->assertJson(['success' => false]);
    }

    public function test_user_can_return_books()
    {
        [$user, $headers] = $this->authenticate();

        $book = Book::factory()->create(['book_status_id' => 2]);

        $borrowing = Borrowing::factory()->create([
            'user_id' => $user->id,
            'book_id' => $book->id,
            'returned_at' => null,
        ]);

        $response = $this->postJson("/api/v1/users/{$user->id}/borrowings/return", [
            'book_ids' => [$book->id],
        ], $headers);

        $response->assertStatus(200)
            ->assertJson(['success' => true]);

        $this->assertDatabaseHas('borrowings', [
            'id' => $borrowing->id,
            'returned_at' => now(), 
        ]);

        $book->refresh();
        $this->assertEquals(1, $book->book_status_id); 
    }

    public function test_get_current_borrower_of_a_book()
    {
        [$user, $headers] = $this->authenticate();

        $book = Book::factory()->create(['book_status_id' => 2]);

        Borrowing::factory()->create([
            'user_id' => $user->id,
            'book_id' => $book->id,
            'borrowed_at' => now()->subDays(2),
            'due_date' => now()->addDays(12),
            'returned_at' => null,
        ]);

        $response = $this->getJson("/api/v1/borrowings/book/{$book->id}/current-borrower", $headers);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'user' => ['id', 'name', 'email'],
                    'borrowed_at',
                    'due_date',
                ]
            ]);
    }

    public function test_get_current_borrower_returns_404_if_not_found()
    {
        [$user, $headers] = $this->authenticate();

        $book = Book::factory()->create();

        $response = $this->getJson("/api/v1/borrowings/book/{$book->id}/current-borrower", $headers);

        $response->assertStatus(404)
            ->assertJson([
                'success' => false,
                'message' => 'No active borrower for this book',
            ]);
    }

    public function test_filter_books_returns_available_and_to_return()
    {
        [$user, $headers] = $this->authenticate();

        $availableBooks = Book::factory()->count(2)->create(['book_status_id' => 1]);

        $borrowedBooks = Book::factory()->count(2)->create(['book_status_id' => 2]);

        foreach ($borrowedBooks as $book) {
            Borrowing::factory()->create([
                'user_id' => $user->id,
                'book_id' => $book->id,
                'borrowed_at' => now()->subDays(3),
                'due_date' => now()->addDays(11),
                'returned_at' => null,
            ]);
        }

        $response = $this->getJson('/api/v1/borrowings/books/filter', $headers);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Filtered books',
            ])
            ->assertJsonStructure([
                'data' => [
                    'available' => [['id', 'title', 'author_id', 'book_status_id']],
                    'to_return' => [['id', 'title', 'author_id', 'book_status_id']],
                    'filter',
                ]
            ]);

        $this->assertCount(2, $response->json('data.available'));
        $this->assertCount(2, $response->json('data.to_return'));
    }
}
