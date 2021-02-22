<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MusicGenre;

class MusicGenreController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', MusicGenre::class);
        return MusicGenre::all();
    }
    public function show(MusicGenre $genre)
    {
        $this->authorize('view', $genre);
        return $genre;
    }
    public function store(Request $request)
    {
        $this->authorize('create', MusicGenre::class);
        $genre = MusicGenre::create($request->all());
        return response()->json($genre, 201);
    }
    public function update(Request $request, MusicGenre $genre)
    {
        $this->authorize('update', $genre);
        $genre->update($request->all());
        return response()->json($genre, 200);
    }
    public function delete(MusicGenre $genre)
    {
        $this->authorize('delete', $genre);
        $genre->delete();
        return response()->json(null, 204);
    }
}
