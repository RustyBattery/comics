<?php

namespace App\Http\Controllers;

use App\Http\Requests\BaseRequest;
use App\Http\Requests\SubscriptionCreateRequest;
use App\Http\Resources\SubscriptionResource;
use App\Models\Author;
use App\Models\Subscription;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SubscriptionController extends Controller
{
    public function get_author_subscriptions(Author $author){
        return response(SubscriptionResource::collection($author->subscriptions()->get()), 200);
    }

    public function create(SubscriptionCreateRequest $request){
        if(!auth()->user()->author()->first()){
            return response(["message" => "This user is not the author!"], 403);
        }
        $data = $request->validated();
        $author = auth()->user()->author()->first();
        if (isset($data['photo'])) {
            $imagePath = Storage::disk('public')->put('', $data['photo']);
            $data['photo'] = 'storage/'.$imagePath;
        }
        $author->subscriptions()->create($data);
        return response([], 200);
    }

    public function get_user_subscription(Request $request){
        $is_active = $request->is_active ?? null;
        $query = auth()->user()->subscriptions()->orderByDesc('user_subscriptions.created_at');
        if($is_active === true){
            $query->whereDate('user_subscriptions.date_end', '>', Carbon::now());
        }
        if($is_active === false){
            $query->whereDate('user_subscriptions.date_end', '<=', Carbon::now());
        }
        return response(SubscriptionResource::collection($query->get()), 200);
    }
}
