<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PeminjamanResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'status' => $this->returned_at ? 'dikembalikan' : 'dipinjam',
            'borrowed_at' => $this->borrowed_at,
            'returned_at' => $this->returned_at,
            'pengguna' => $this->whenLoaded('pengguna', fn () => [
                'id' => $this->pengguna->id,
                'nama' => $this->pengguna->nama,
                'email' => $this->pengguna->email,
            ]),
            'media' => $this->whenLoaded('media', fn () => [
                'id' => $this->media->id,
                'judul' => $this->media->judul,
                'jenis' => $this->media->jenis,
                'is_available' => $this->media->is_available,
            ]),
        ];
    }
}
