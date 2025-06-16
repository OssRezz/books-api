<?php

namespace Database\Seeders;

use App\Models\BookStatus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BookStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $book_statuses = [
            [
                'name' => 'Available',
                'background_color' => '#00FF00',
                'text_color' => '#000000',
            ],
            [
                'name' => 'Borrowed',
                'background_color' => '#FFFF00',
                'text_color' => '#000000',
            ]
        ];
        BookStatus::insert($book_statuses);
    }
}
