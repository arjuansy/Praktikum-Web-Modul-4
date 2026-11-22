<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Media extends Model
{
    protected $fillable = ['judul', 'jenis', 'genre', 'tahun'];

    protected $casts = [
        'tahun' => 'integer',
    ];

    public function penggunas(): BelongsToMany
    {
        return $this->belongsToMany(Pengguna::class, 'media_pengguna');
    }
}
