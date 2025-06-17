<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BookStatus extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'background_color',
        'text_color',
    ];
}
