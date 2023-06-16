<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChapterCreateRequest;
use App\Models\Book;
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
}
