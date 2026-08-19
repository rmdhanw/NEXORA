<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRespondentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'project_id' => 'required|exists:projects,id',
            'form_id' => 'nullable|exists:forms,id',
            'album.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'keterangan' => 'nullable|string',
            'status' => 'required|in:belum_diinput,sudah_diinput',
        ];
    }
}
