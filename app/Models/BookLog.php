<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookLog extends Model
{
    protected $fillable = [
        'user_id',
        'book_id',
        'status',
        'rating',
        'notes',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function book()
    {
        return $this->belongsTo(Book::class);
    }
}
