<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\Author;
use Illuminate\Database\Seeder;

class BookSeeder extends Seeder
{
    public function run(): void
    {
        $faker = \Faker\Factory::create();
        $baseBooks = [
            ['Clean Code', 'Robert C. Martin', 2008],
            ['Clean Architecture', 'Robert C. Martin', 2017],
            ['Refactoring', 'Martin Fowler', 1999],
            ['Domain-Driven Design', 'Eric Evans', 2003],
            ['Test Driven Development', 'Kent Beck', 2003],
            ['The Art of Computer Programming', 'Donald Knuth', 1968],
            ['Code Complete', 'Steve McConnell', 2004],
            ['JavaScript: The Good Parts', 'Douglas Crockford', 2008],
            ['The Pragmatic Programmer', 'Andrew Hunt', 1999],
            ['Programming Ruby', 'David Thomas', 2000],
            ['The C++ Programming Language', 'Bjarne Stroustrup', 2013],
            ['The Ruby Programming Language', 'Yukihiro Matsumoto', 2008],
            ['Just for Fun', 'Linus Torvalds', 2001],
            ['You Don’t Know JS', 'Dan Abramov', 2015],
            ['Node.js Design Patterns', 'Ryan Dahl', 2016],
            ['Harry Potter and the Sorcerer\'s Stone', 'J.K. Rowling', 1997],
            ['Game of Thrones', 'George R.R. Martin', 1996],
            ['The Hobbit', 'J.R.R. Tolkien', 1937],
            ['The Shining', 'Stephen King', 1977],
            ['Foundation', 'Isaac Asimov', 1951],
        ];

        $bookRecords = [];

        foreach ($baseBooks as [$title, $authorName, $year]) {
            $author = Author::where('name', $authorName)->first();
            if ($author) {
                $bookRecords[] = [
                    'title' => $title,
                    'author_id' => $author->id,
                    'isbn' => '978-' . rand(1000000000, 9999999999),
                    'publication_year' => $year,
                    'book_status_id' => 1,
                    'image' => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        // Generar libros adicionales aleatorios hasta llegar a 50
        $authors = Author::all();
        while (count($bookRecords) < 50) {
            $bookRecords[] = [
                'title' => $faker->sentence(3),
                'author_id' => $authors->random()->id,
                'isbn' => '978-' . rand(1000000000, 9999999999),
                'publication_year' => $faker->year,
                'book_status_id' => 1,
                'image' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        Book::insert($bookRecords);
    }
}
