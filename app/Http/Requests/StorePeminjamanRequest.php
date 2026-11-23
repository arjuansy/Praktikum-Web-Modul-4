<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePeminjamanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'pengguna_id' => ['required', 'integer', 'exists:penggunas,id'],
            'media_id' => ['required', 'integer', 'exists:media,id'],
        ];
    }
}
