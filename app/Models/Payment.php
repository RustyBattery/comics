<?php

namespace App\Models;

use App\Events\PaymentSuccess;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Http;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'status',
        'external_id'
    ];

    public function init($amount, $success_url, $event,  $description = ""){
        $body = [
            "amount" => [
                "value" => $amount/100,
                "currency" => "RUB"
            ],
            "capture" => true,
            "confirmation" => [
                "type" => "redirect",
                "return_url" => $success_url
            ],
            "description" => $description
        ];
        $response = Http::withBasicAuth(env('YOOKASSA_ID'), env('YOOKASSA_KEY'))
            ->withHeaders(['Idempotence-Key' => $this->id + 1000])
            ->post('https://api.yookassa.ru/v3/payments', $body);
        $external_id = $response->json()['id'];
        $this->external_id = $external_id;
        $this->save();
        $event::dispatch($this);
        return $response->json()['confirmation']['confirmation_url'];
    }

    public function check(){
        $count_attempt = 3;
        while ($count_attempt > 0){
            $response = Http::withBasicAuth(env('YOOKASSA_ID'), env('YOOKASSA_KEY'))
                ->get('https://api.yookassa.ru/v3/payments/'.$this->external_id);
            if($response->json()['status'] == 'succeeded'){
                $this->status = $response->json()['status'];
                $this->save();
                PaymentSuccess::dispatch($this);
                return true;
            }
            $count_attempt--;
            sleep(60);
        }
        $this->delete();
        return false;
    }

    public function subscription(){
        $user_subscription = $this->hasMany(UserSubscription::class)->first();
        return $user_subscription ? $user_subscription->subscription()->first() : null;
    }

    public function donation(){
        return $this->hasMany(Donation::class)->first();
    }

    public function get_author_and_amount(){
        $subscription = $this->subscription() ?? null;
        if($subscription){
            return ["amount" => $subscription->price, "author_id" => $subscription->author->id];
        }
        $donation = $this->donation() ?? null;
        if($donation){
            return ["amount" => $donation->amount, "author_id" => $donation->author_id];
        }
    }
}
