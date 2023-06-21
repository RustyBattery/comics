<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthorCreateRequest;
use App\Http\Requests\BaseRequest;
use App\Http\Resources\AuthorListResource;
use App\Http\Resources\AuthorResource;
use App\Http\Resources\AuthorStatisticsResource;
use App\Http\Resources\UserShortResource;
use App\Models\Author;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AuthorController extends Controller
{
    public function index(BaseRequest $request){
        $data = $request->validated();
        $query = Author::query();
        if(isset($data['search'])){
            $query->where(function ($query) use ($data){
                foreach ($data['search']['fields'] as $field){
                    $query->orWhere($field, 'ilike', '%'.$data['search']['value'].'%');
                }
            });
        }
        $authors = $query->get();
        return response(AuthorListResource::collection($authors), 200);
    }

    public function get(Author $author){
        return response(AuthorResource::make($author), 200);
    }

    public function create(AuthorCreateRequest $request){
        $data = $request->validated();
        if(auth()->user()->author()->first()){
            return response(["message" => "This user is already an author!"], 400);
        }
        if (isset($data['photo'])) {
            $imagePath = Storage::disk('public')->put('', $data['photo']);
            $data['photo'] = 'storage/'.$imagePath;
        }
        auth()->user()->roles()->attach(Role::where('slug', 'author')->first());
        $author = auth()->user()->author()->create($data);
        return response(["author_id" => $author->id], 200);
    }

    public function statistics(){
        if(!auth()->user()->author()->first()){
            return response(["message" => "This user is not the author!"], 403);
        }
        return response(AuthorStatisticsResource::make(auth()->user()->author()->first()), 200);
    }

    public function get_followers(){
        if(!auth()->user()->author()->first()){
            return response(["message" => "This user is not the author!"], 403);
        }
        $author = auth()->user()->author()->first();
        return response(UserShortResource::collection($author->followers()->get()), 200);
    }
}
