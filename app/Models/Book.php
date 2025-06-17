<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Book extends Model
{
    use HasFactory;

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
