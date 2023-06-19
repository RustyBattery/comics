<?php

namespace App\Http\Controllers;

use App\Http\Requests\BaseRequest;
use App\Http\Requests\ChapterCreateRequest;
use App\Http\Resources\ModerationResource;
use App\Models\Book;
use App\Models\Chapter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Spatie\PdfToImage\Pdf;


class ChapterController extends Controller
{
    public function get(){

    }

    public function create(Book $book, ChapterCreateRequest $request){
        if(!auth()->user()->author()->first()){
            return response(["message" => "This user is not the author!"], 403);
        }
        $data = $request->validated();
        foreach ($data['chapters'] as $chapter){
            $newChapter = $book->chapters()->create($chapter);
            $pdfPath = 'storage/'.Storage::disk('public')->put('', $chapter['file']);
            $pdf = new Pdf($pdfPath);
            for ($i = 1; $i <= $pdf->getNumberOfPages(); $i++) {
                $imgName = 'c'.$newChapter->id.'p'.$i.uniqid().'.jpg';
                $pdf->setPage($i)
                    ->saveImage('storage/'.$imgName);
                $newChapter->pages()->create(["number" => $i, "url" =>'storage/'.$imgName]);
            }
        }
        return response([], 200);
    }

    public function get_moderation(BaseRequest $request){
        $data = $request->validated();
        if(!auth()->user()->author()->first()){
            return response(["message" => "This user is not the author!"], 403);
        }
        $author =  auth()->user()->author()->first();

        $query = $author->chapters();
        if(isset($data['filters'])){
            foreach ($data['filters'] as $filter){
                if(count($filter['values']) > 1){
                    $query->whereIn($filter['field'], $filter['values']);
                }
                else{
                    $query->where('chapters.'.$filter['field'], $filter['operator'], $filter['values'][0]);
                }
            }
        }

        $chapters = $query->orderByDesc('created_at')->get();
        return response(ModerationResource::collection($chapters), 200);
    }

    public function approve(Chapter $chapter){
        $chapter->status = "approved";
        $chapter->save();
        return response([], 200);
    }
}
