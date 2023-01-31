<?php

namespace App\Http\Requests\Ocr;

use Illuminate\Foundation\Http\FormRequest;

class OcrCreateRequest extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        return [
            //
            'fileInput' => 'required|file|mimes:pdf,jpg,png,jpeg,docx,doc|max:1024'
        ];
    }
}