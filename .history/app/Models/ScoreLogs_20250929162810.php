<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ScoreLogs extends Model
{
    protected $table = 'score_logs';

    protected $fillable = [
        'user_id',
        'books_id',
        'title',
        'score'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function book()
    {
        return $this->belongsTo(Book::class, 'books_id');
    }
}
