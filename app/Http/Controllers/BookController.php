<?php

namespace App\Http\Controllers;

use App\Http\Requests\BookCreateRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BookController extends Controller
{
    public function get(){

    }

    public function create(BookCreateRequest $request){
        if(!auth()->user()->author()->first()){
            return response(["message" => "This user is not the author!"], 403);
        }
        $data = $request->validated();
        $author = auth()->user()->author()->first();
        if (isset($data['photo'])) {
            $imagePath = Storage::disk('public')->put('', $data['photo']);
            $data['photo'] = 'storage/'.$imagePath;
        }
        $author->books()->create($data);
        return response([], 200);
    }
}
