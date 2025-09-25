<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookClassification extends Model
{
    protected $fillable = [
        'classification',
    ];

    /**
     * Relasi ke Book
     * Satu classification bisa punya banyak book.
     */
    public function books()
    {
        return $this->hasMany(Book::class, 'classification_id');
    }
}
