<?php

namespace App\Http\Controllers;

use App\Http\Requests\BaseRequest;
use App\Http\Requests\BookCreateRequest;
use App\Http\Resources\AuthorBooksResource;
use App\Http\Resources\BookResource;
use App\Models\Author;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Builder;

class BookController extends Controller
{
    public function index(BaseRequest $request){
        $data = $request->validated();
        $query = Book::query();
        if(isset($data['filters'])){
            foreach ($data['filters'] as $filter){
                preg_match('/(.*)\.(.*)/', $filter['field'], $matches);
                if(count($filter['values']) > 1){
                    if($matches[0]){
                        $query->whereHas($matches[1], function($query) use ($matches, $filter) {
                            $query->whereIn($matches[1].'.'.$matches[2], $filter['values']);
                        });
                    }
                    else{
                        $query->whereIn($filter['field'], $filter['values']);
                    }
                }
                else{
                    if($matches[0]){
                        $query->whereHas($matches[1], function($query) use ($matches, $filter) {
                            $query->where($matches[1].'.'.$matches[2], $filter['operator'], $filter['values'][0]);
                        });
                    }
                    else{
                        $query->where($filter['field'], $filter['operator'], $filter['values'][0]);
                    }
                }
            }
        }

        if(isset($data['search'])){
            $query->where(function ($query) use ($data){
                foreach ($data['search']['fields'] as $field){
                    $query->orWhere($field, 'ilike', '%'.$data['search']['value'].'%');
                }
            });
        }

        $query->whereHas('chapters', function (Builder $query) {
            $query->where('status', 'approved');
        });
        $books = $query->get();
        return response(AuthorBooksResource::collection($books), 200);
    }

    public function get(Book $book){
        return response(BookResource::make($book), 200);
    }

    public function get_author_books(Author $author,  BaseRequest $request){
        $data = $request->validated();
        $query = $author->books();
        if(isset($data['filters'])){
            foreach ($data['filters'] as $filter){
                if(count($filter['values']) > 1){
                    $query->whereIn($filter['field'], $filter['values']);
                }
                else{
                    $query->where($filter['field'], $filter['operator'], $filter['values'][0]);
                }
            }
        }
        if(!auth()->user() || auth()->user()->author()->first()?->id != $author->id){
            $query->whereHas('chapters', function (Builder $query) {
                $query->where('status', 'approved');
            });
        }
        $books = $query->get();
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
        if(isset($data['genres'])){
            $book->genres()->attach($data['genres']);
        }

        return response(AuthorBooksResource::make($book), 200);
    }
}
