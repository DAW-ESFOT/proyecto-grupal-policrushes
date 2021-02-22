<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MovieGenre;

class MovieGenreController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', MovieGenre::class);
        return MovieGenre::all();
    }
    public function show(MovieGenre $genre)
    {
        $this->authorize('view', $genre);
        return $genre;
    }
    public function store(Request $request)
    {
        $this->authorize('create', MovieGenre::class);
        $genre = MovieGenre::create($request->all());
        return response()->json($genre, 201);
    }
    public function update(Request $request, MovieGenre $genre)
    {
        $this->authorize('update', $genre);
        $genre->update($request->all());
        return response()->json($genre, 200);
    }
    public function delete(MovieGenre $genre)
    {
        $this->authorize('delete', $genre);
        $genre->delete();
        return response()->json(null, 204);
    }

}
