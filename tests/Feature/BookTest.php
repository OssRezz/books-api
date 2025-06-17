<?php

namespace Tests\Feature;

use App\Models\Author;
use App\Models\Book;
use App\Models\BookStatus;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class BookTest extends TestCase
{
    use RefreshDatabase;

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

        return [
            'Authorization' => "Bearer $token",
            'Accept' => 'application/json'
        ];
    }

    public function test_can_list_books()
    {
        $headers = $this->authenticate();

        $response = $this->getJson('/api/v1/books', $headers);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'data',
                ],
                'status',
            ]);
    }

    public function test_can_create_book()
    {
        $headers = $this->authenticate();

        $author = Author::factory()->create();
        $status = BookStatus::factory()->create();

        Storage::fake('public');

        $file = UploadedFile::fake()->image('cover.jpg');

        $data = [
            'title' => 'Test Book',
            'author_id' => $author->id,
            'isbn' => '1234567890123',
            'publication_year' => now()->year,
            'book_status_id' => $status->id,
            'image_book' => $file,
        ];

        $response = $this->postJson('/api/v1/books', $data, $headers);

        $response->assertStatus(201)
            ->assertJson([
                'success' => true,
                'message' => 'Book created',
            ]);

        $book = Book::latest()->first(); 
        $this->assertNotNull($book->image);

        // El idle marca error, pero el archivo si se guarda
        Storage::disk('public')->assertExists('books/' . $book->image); 
    }
    public function test_can_update_book()
    {
        $headers = $this->authenticate();

        $author = Author::factory()->create();
        $status = BookStatus::factory()->create();

        $book = Book::create([
            'title' => 'Old Title',
            'author_id' => $author->id,
            'isbn' => '1234567890123',
            'publication_year' => 2020,
            'book_status_id' => $status->id,
            'image' => null,
        ]);

        $updatedData = [
            'title' => 'Updated Title',
            'author_id' => $author->id,
            'isbn' => '1234567890124',
            'publication_year' => now()->year,
            'book_status_id' => $status->id,
        ];

        $response = $this->putJson("/api/v1/books/{$book->id}", $updatedData, $headers);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Book updated',
                'data' => ['title' => 'Updated Title'],
            ]);
    }

    public function test_can_delete_book()
    {
        $headers = $this->authenticate();

        $author = Author::factory()->create();
        $status = BookStatus::factory()->create();

        $book = Book::create([
            'title' => 'To Delete',
            'author_id' => $author->id,
            'isbn' => '1234567890123',
            'publication_year' => now()->year,
            'book_status_id' => $status->id,
        ]);

        $response = $this->deleteJson("/api/v1/books/{$book->id}", [], $headers);

        $response->assertStatus(204);
        $this->assertDatabaseMissing('books', ['id' => $book->id]);
    }
}
