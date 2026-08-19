<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreFormRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'project_id' => 'required|exists:projects,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'has_photo' => 'nullable',
            'has_age_calc' => 'nullable',
            'fields' => 'nullable|array',
            'fields.*.name' => 'required_with:fields|string',
            'fields.*.type' => 'required_with:fields|string|in:text,number,date',
        ];
    }
}
