<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePeminjamanRequest;
use App\Http\Resources\PeminjamanResource;
use App\Models\Media;
use App\Models\Peminjaman;
use App\Models\Pengguna;
use Illuminate\Support\Facades\DB;

class PeminjamanController extends Controller
{
    public function store(StorePeminjamanRequest $request)
    {
        $data = $request->validated();

        return DB::transaction(function () use ($data) {
            $mediaClaimed = Media::query()
                ->whereKey($data['media_id'])
                ->where('is_available', true)
                ->update(['is_available' => false]);

            if ($mediaClaimed === 0) {
                return response()->json(['message' => 'Media sedang dipinjam'], 409);
            }

            $pengguna = Pengguna::findOrFail($data['pengguna_id']);
            $collectionExisted = $pengguna->media()->whereKey($data['media_id'])->exists();

            $peminjaman = Peminjaman::create([
                'pengguna_id' => $data['pengguna_id'],
                'media_id' => $data['media_id'],
                'borrowed_at' => now(),
                'collection_existed' => $collectionExisted,
            ]);

            $pengguna->media()->syncWithoutDetaching([$data['media_id']]);

            return (new PeminjamanResource($peminjaman->load(['pengguna', 'media'])))
                ->response()
                ->setStatusCode(201);
        });
    }

    public function returnMedia(Peminjaman $peminjaman)
    {
        return DB::transaction(function () use ($peminjaman) {
            $updated = Peminjaman::query()
                ->whereKey($peminjaman->id)
                ->whereNull('returned_at')
                ->update(['returned_at' => now()]);

            if ($updated === 0) {
                return response()->json(['message' => 'Peminjaman sudah dikembalikan'], 409);
            }

            $peminjaman->media()->update(['is_available' => true]);
            if (! $peminjaman->collection_existed) {
                $peminjaman->pengguna->media()->detach($peminjaman->media_id);
            }

            return (new PeminjamanResource($peminjaman->fresh()->load(['pengguna', 'media'])))
                ->response()
                ->setStatusCode(200);
        });
    }
}
