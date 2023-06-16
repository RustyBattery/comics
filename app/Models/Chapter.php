<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chapter extends Model
{
    use HasFactory;

    protected $fillable = [
        'book_id',
        'number',
        'name',
        'description',
        'subscription_id',
        'is_agreed',
        'reason',
    ];

    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }

    public function pages()
    {
        return $this->hasMany(Page::class);
    }
}
