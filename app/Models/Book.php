<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'google_books_id',
        'title',
        'author',
        'cover_image_url',
        'description',
        'published_date',
    ];

    public function logs()
    {
        return $this->hasMany(BookLog::class);
    }
}
