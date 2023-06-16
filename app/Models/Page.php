<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    use HasFactory;

    protected $fillable = [
        'chapter_id',
        'number',
        'url',
    ];

    public function chapter()
    {
        return $this->belongsTo(Chapter::class);
    }
}
