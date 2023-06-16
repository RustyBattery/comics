<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ChapterCreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
//            "chapters.*" => "required",
            "chapters.*.number" => "required|integer",
            "chapters.*.name" => "required|string",
            "chapters.*.description" => "nullable|string",
            "chapters.*.subscription_id" => "nullable|exists:subscriptions,id",
            "chapters.*.file" => "required|mimes:pdf",
        ];
    }
}
