<?php

namespace App\Http\Controllers;

use App\Http\Requests\BookCreateRequest;
use App\Http\Resources\AuthorBooksResource;
use App\Models\Author;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Builder;

class BookController extends Controller
{
    public function get(){

    }

    public function get_author_books(Author $author){
        if(auth()->user() && auth()->user()->author()->id == $author->id){
            $books = $author->books()->all();
        }
        else{
            $books = $author->books()->whereHas('chapters', function (Builder $query) {
                $query->where('status', 'approved');
            })->get();
        }
        return response(AuthorBooksResource::collection($books), 200);
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
        $book = $author->books()->create($data);
        $book->genres()->attach($data['genres']);

        return response(AuthorBooksResource::make($book), 200);
    }

    public function get_moderation(){

    }
}
