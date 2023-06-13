<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthorCreateRequest;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AuthorController extends Controller
{
    public function get(){
        if(!auth()->user()->author()->first()){
            return response(["message" => "This user is not the author!"], 403);
        }
        return response(["author" => auth()->user()->author()->first()], 200);
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
        auth()->user()->author()->create($data);
        return response("OK", 200);
    }
}
