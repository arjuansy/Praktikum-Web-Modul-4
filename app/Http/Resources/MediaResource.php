<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MediaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'judul' => $this->judul,
            'jenis' => $this->jenis,
            'genre' => $this->genre,
            'tahun' => $this->tahun,
            'is_available' => $this->is_available,
            'pengguna' => $this->whenLoaded('penggunas', fn () => $this->penggunas->map(fn ($pengguna) => [
                'id' => $pengguna->id,
                'nama' => $pengguna->nama,
                'email' => $pengguna->email,
            ])),
        ];
    }
}
