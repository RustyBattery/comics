<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthorCreateRequest;
use App\Http\Resources\AuthorResource;
use App\Models\Author;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AuthorController extends Controller
{
    public function index(){
        return response(["message" => "authors list"], 200);
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
}
