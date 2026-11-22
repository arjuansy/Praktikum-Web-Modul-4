<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreMediaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $isPatch = $this->isMethod('PATCH');
        $mediaId = $this->route('media')?->id;

        return [
            'judul' => [
                $isPatch ? 'sometimes' : 'required',
                'string',
                'max:255',
                Rule::unique('media', 'judul')->ignore($mediaId),
            ],
            'jenis' => [$isPatch ? 'sometimes' : 'required', 'string', 'max:100'],
            'genre' => ['nullable', 'string', 'max:100'],
            'tahun' => ['nullable', 'integer', 'between:1888,2100'],
        ];
    }

    public function messages(): array
    {
        return [
            'judul.required' => 'Judul media wajib diisi',
            'judul.unique' => 'Judul media sudah digunakan',
            'jenis.required' => 'Jenis media wajib diisi',
        ];
    }
}
