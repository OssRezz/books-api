<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    protected $fillable = [
        'title',
        'author_id',
        'isbn',
        'publication_year',
        'image',
        'book_status_id',
    ];

    public function author()
    {
        return $this->belongsTo(Author::class);
    }

    public function book_status()
    {
        return $this->belongsTo(BookStatus::class);
    }
}
