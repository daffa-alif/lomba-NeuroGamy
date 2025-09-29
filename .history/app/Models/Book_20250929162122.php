<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    
    protected $fillable = [
        'classification_id',
        'book_title',
        'file_name',
        'book_description',
    ];

    /**
     * Relasi ke BookClassification
     * Setiap buku punya satu classification.
     */
    public function classification()
    {
        return $this->belongsTo(BookClassification::class, 'classification_id');
    }
}
