<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDocumentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['sometimes', 'required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'category_id' => ['sometimes', 'required', 'exists:document_categories,id'],
            'access_level' => ['sometimes', 'required', 'in:public,department,private'],
        ];
    }
}
