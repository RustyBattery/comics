<?php

namespace App\Http\Controllers;

use App\Http\Requests\SubscriptionCreateRequest;
use App\Http\Resources\SubscriptionResource;
use App\Models\Author;
use App\Models\Subscription;
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
}
