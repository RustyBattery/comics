<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'author_id',
        'name',
        'description',
        'photo',
        'price',
    ];

    public function author()
    {
        return $this->belongsTo(Author::class);
    }

    public function chapters()
    {
        return $this->hasMany(Chapter::class);
    }
}
