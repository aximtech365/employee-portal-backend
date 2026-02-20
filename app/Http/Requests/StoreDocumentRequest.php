<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDocumentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'file' => ['required', 'file', 'mimes:pdf,docx,xlsx,jpg,jpeg,png', 'max:10240'],
            'category_id' => ['required', 'exists:document_categories,id'],
            'department_id' => ['required', 'exists:departments,id'],
            'access_level' => ['required', 'in:public,department,private'],
        ];
    }

    public function messages(): array
    {
        return [
            'file.max' => 'File size must not exceed 10MB.',
            'file.mimes' => 'File must be a PDF, DOCX, XLSX, JPG, or PNG.',
        ];
    }
}
