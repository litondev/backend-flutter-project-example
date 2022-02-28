<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Traits\RequestTrait;

class ProductRequest extends FormRequest
{
    use RequestTrait;

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            "title" => "string|required|max:50",
            "price" => "required|numeric",
            "stock" => "required|numeric",
            "description" => "nullable"
        ];
    }
}
