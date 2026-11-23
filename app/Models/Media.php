<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Media extends Model
{
    protected $fillable = ['judul', 'jenis', 'genre', 'tahun', 'is_available'];

    protected $casts = [
        'tahun' => 'integer',
        'is_available' => 'boolean',
    ];

    public function penggunas(): BelongsToMany
    {
        return $this->belongsToMany(Pengguna::class, 'media_pengguna');
    }

    public function peminjamans(): HasMany
    {
        return $this->hasMany(Peminjaman::class);
    }
}
