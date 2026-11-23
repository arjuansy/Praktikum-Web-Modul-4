<?php

namespace Tests\Feature;

use App\Models\Media;
use App\Models\Pengguna;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FlipPlayLoanTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_borrow_media_and_return_it(): void
    {
        $pengguna = Pengguna::create([
            'nama' => 'Arjuan',
            'email' => 'arjuan@example.com',
        ]);
        $media = Media::create([
            'judul' => 'Film Demo',
            'jenis' => 'Film',
        ]);

        $borrowResponse = $this->postJson('/api/peminjaman', [
            'pengguna_id' => $pengguna->id,
            'media_id' => $media->id,
        ]);

        $borrowResponse
            ->assertCreated()
            ->assertJsonPath('data.status', 'dipinjam')
            ->assertJsonPath('data.media.is_available', false);

        $peminjamanId = $borrowResponse->json('data.id');

        $this->assertDatabaseHas('media', [
            'id' => $media->id,
            'is_available' => false,
        ]);

        $this->getJson('/api/media')
            ->assertOk()
            ->assertJsonPath('data.0.pengguna.0.id', $pengguna->id);

        $this->getJson("/api/penggunas/{$pengguna->id}")
            ->assertOk()
            ->assertJsonPath('data.peminjaman.0.status', 'dipinjam');

        $this->patchJson("/api/peminjaman/{$peminjamanId}/kembali")
            ->assertOk()
            ->assertJsonPath('data.status', 'dikembalikan')
            ->assertJsonPath('data.media.is_available', true);

        $this->assertDatabaseHas('media', [
            'id' => $media->id,
            'is_available' => true,
        ]);

        $this->getJson('/api/media')
            ->assertOk()
            ->assertJsonPath('data.0.pengguna', []);

        $this->patchJson("/api/peminjaman/{$peminjamanId}/kembali")
            ->assertConflict();

        $this->deleteJson("/api/media/{$media->id}")
            ->assertConflict();
    }

    public function test_media_cannot_be_borrowed_by_two_users_at_once(): void
    {
        $firstUser = Pengguna::create([
            'nama' => 'Peminjam Pertama',
            'email' => 'pertama@example.com',
        ]);
        $secondUser = Pengguna::create([
            'nama' => 'Peminjam Kedua',
            'email' => 'kedua@example.com',
        ]);
        $media = Media::create([
            'judul' => 'Film Eksklusif',
            'jenis' => 'Film',
        ]);

        $this->postJson('/api/peminjaman', [
            'pengguna_id' => $firstUser->id,
            'media_id' => $media->id,
        ])->assertCreated();

        $this->postJson('/api/peminjaman', [
            'pengguna_id' => $secondUser->id,
            'media_id' => $media->id,
        ])->assertConflict();

        $this->assertDatabaseCount('peminjaman', 1);
    }

    public function test_return_keeps_a_preexisting_collection_entry(): void
    {
        $pengguna = Pengguna::create([
            'nama' => 'Pengguna Koleksi',
            'email' => 'koleksi@example.com',
        ]);
        $media = Media::create([
            'judul' => 'Film Koleksi',
            'jenis' => 'Film',
        ]);
        $pengguna->media()->attach($media->id);

        $peminjamanId = $this->postJson('/api/peminjaman', [
            'pengguna_id' => $pengguna->id,
            'media_id' => $media->id,
        ])->assertCreated()->json('data.id');

        $this->patchJson("/api/peminjaman/{$peminjamanId}/kembali")->assertOk();

        $this->assertDatabaseHas('media_pengguna', [
            'pengguna_id' => $pengguna->id,
            'media_id' => $media->id,
        ]);
    }

    public function test_user_can_be_created_with_post(): void
    {
        $this->postJson('/api/penggunas', [
            'nama' => 'Pengguna Baru',
            'email' => 'baru@example.com',
        ])
            ->assertCreated()
            ->assertJsonPath('data.nama', 'Pengguna Baru');

        $this->postJson('/api/penggunas', [
            'nama' => 'Pengguna Duplikat',
            'email' => 'baru@example.com',
        ])->assertUnprocessable();
    }
}
