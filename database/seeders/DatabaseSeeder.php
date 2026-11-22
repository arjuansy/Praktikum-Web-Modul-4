<?php

namespace Database\Seeders;

use App\Models\Media;
use App\Models\Pengguna;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $pengguna = Pengguna::create([
            'nama' => 'Iqbal',
            'email' => 'iqbal@example.com',
        ]);

        Media::create([
            'judul' => 'Interstellar',
            'jenis' => 'Film',
            'genre' => 'Sci-Fi',
            'tahun' => 2014,
        ]);

        Media::create([
            'judul' => 'The Legend of Zelda',
            'jenis' => 'Game',
            'genre' => 'Adventure',
            'tahun' => 1986,
        ]);

        $pengguna->media()->attach(Media::first()->id);
    }
}
