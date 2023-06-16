<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'author_id',
        'name',
        'description',
        'photo',
        'status',
        'price',
    ];

    public function genres()
    {
        return $this->belongsToMany(Genre::class, 'book_genres')->withTimestamps();
    }

    public function author()
    {
        return $this->belongsTo(Author::class);
    }

    public function chapters()
    {
        return $this->hasMany(Chapter::class);
    }
}
