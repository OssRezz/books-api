<?php

namespace Database\Factories;

use App\Models\Book;
use App\Models\Author;
use App\Models\BookStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

class BookFactory extends Factory
{
    protected $model = Book::class;

    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(3),
            'author_id' => Author::factory(),
            'isbn' => $this->faker->isbn13(),
            'publication_year' => $this->faker->year(),
            'book_status_id' => BookStatus::factory(),
            'image' => null,
        ];
    }
}