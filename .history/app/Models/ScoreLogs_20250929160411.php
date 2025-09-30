<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ScoreLog extends Model
{
    protected $table = 'score_logs';

    protected $fillable = [
        'user_id',
        'books_id',
        'title',
        'score'
    ];

    /**
     * Relasi ke User
     * Satu log hanya dimiliki oleh satu user.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relasi ke Book
     * Satu log hanya dimiliki oleh satu buku.
     */
    public function book()
    {
        return $this->belongsTo(Book::class, 'books_id');
    }
}
