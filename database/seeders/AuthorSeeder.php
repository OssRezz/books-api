<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Author;

class AuthorSeeder extends Seeder
{
    public function run(): void
    {
        $authors = [
            ['name' => 'Robert C. Martin'],
            ['name' => 'Martin Fowler'],
            ['name' => 'Eric Evans'],
            ['name' => 'Kent Beck'],
            ['name' => 'Donald Knuth'],
            ['name' => 'Steve McConnell'],
            ['name' => 'Douglas Crockford'],
            ['name' => 'Andrew Hunt'],
            ['name' => 'David Thomas'],
            ['name' => 'Bjarne Stroustrup'],
            ['name' => 'Yukihiro Matsumoto'],
            ['name' => 'Linus Torvalds'],
            ['name' => 'Dan Abramov'],
            ['name' => 'Ryan Dahl'],
            ['name' => 'Brendan Eich'],
            ['name' => 'J.K. Rowling'],
            ['name' => 'George R.R. Martin'],
            ['name' => 'J.R.R. Tolkien'],
            ['name' => 'Stephen King'],
            ['name' => 'Isaac Asimov'],
        ];

        Author::insert($authors);
    }
}
