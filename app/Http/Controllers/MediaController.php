<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMediaRequest;
use App\Http\Resources\MediaResource;
use App\Models\Media;

class MediaController extends Controller
{
    public function index()
    {
        $search = request('search', request('judul', request('q')));
        $media = Media::query()
            ->when($search, fn ($query) => $query->where('judul', 'like', '%'.$search.'%'))
            ->with('penggunas')
            ->get();

        return MediaResource::collection($media);
    }

    public function store(StoreMediaRequest $request)
    {
        $media = Media::create($request->validated());

        return (new MediaResource($media))->response()->setStatusCode(201);
    }

    public function show(Media $media)
    {
        return new MediaResource($media->load('penggunas'));
    }

    public function update(StoreMediaRequest $request, Media $media)
    {
        $media->update($request->validated());

        return new MediaResource($media->load('penggunas'));
    }

    public function destroy(Media $media)
    {
        $media->delete();

        return response()->json(['message' => 'Media berhasil dihapus']);
    }
}
