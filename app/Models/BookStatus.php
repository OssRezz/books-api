<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookStatus extends Model
{
    protected $fillable = [
        'name',
        'background_color',
        'text_color',
    ];
}
