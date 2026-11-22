<?php

namespace App\Http\Controllers;

use App\Http\Resources\PenggunaResource;
use App\Models\Media;
use App\Models\Pengguna;

class PenggunaController extends Controller
{
    public function index()
    {
        return PenggunaResource::collection(Pengguna::with('media')->get());
    }

    public function show(Pengguna $pengguna)
    {
        return new PenggunaResource($pengguna->load('media'));
    }

    public function attach(Pengguna $pengguna, Media $media)
    {
        if ($pengguna->media()->whereKey($media->id)->exists()) {
            return response()->json(['message' => 'Media sudah ada dalam koleksi pengguna'], 409);
        }

        $pengguna->media()->attach($media->id);

        return (new PenggunaResource($pengguna->load('media')))
            ->response()
            ->setStatusCode(200);
    }

    public function detach(Pengguna $pengguna, Media $media)
    {
        if (! $pengguna->media()->whereKey($media->id)->exists()) {
            return response()->json(['message' => 'Media tidak ada dalam koleksi pengguna'], 404);
        }

        $pengguna->media()->detach($media->id);

        return (new PenggunaResource($pengguna->load('media')))
            ->response()
            ->setStatusCode(200);
    }
}
