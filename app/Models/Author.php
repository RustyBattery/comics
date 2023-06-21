<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Author extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nickname',
        'about',
        'photo',
        'balance',
        'withdraw_money',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    public function books()
    {
        return $this->hasMany(Book::class);
    }

    public function chapters()
    {
        return $this->hasManyThrough(Chapter::class, Book::class);
    }

    public function following()
    {
        return $this->belongsToMany(User::class, 'user_favorite_authors')->withTimestamps();
    }

    public function subscribers(){
        $subscriptions = $this->subscriptions()->get();
        $users = collect([]);
        foreach ($subscriptions as $subscription){
            $users = $users->merge($subscription->users()->with('subscriptions')->whereDate('user_subscriptions.date_end', '>', Carbon::now())->get());
        }
        return $users;
    }
}
