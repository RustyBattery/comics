<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BaseRequest extends FormRequest
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
            "filters" => "nullable",
            "filters.*.field" => "required_unless:filters,null",
            "filters.*.operator" => "required_unless:filters,null|in:=,!=,>,<,<=,>=",
            "filters.*.values" => "required_unless:filters,null",
            "search" => "nullable",
            "search.fields" => "required_unless:search,null",
            "search.fields.*" => "required_unless:search,null",
            "search.value" => "required_unless:search,null",
            "sort" => "nullable",
            "sort.field" => "required_unless:sort,null",
            "sort.order_by" => "required_unless:sort,null",
        ];
    }
}
