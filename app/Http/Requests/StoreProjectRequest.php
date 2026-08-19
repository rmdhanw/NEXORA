<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProjectRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nama_project' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'status' => 'required|in:pending,on_progress,completed',
            'has_photo' => 'nullable',
            'has_age_calc' => 'nullable',
            'fields' => 'nullable|array',
            'fields.*.name' => 'required_with:fields|string',
            'fields.*.type' => 'required_with:fields|string|in:text,number,date',
        ];
    }
}
