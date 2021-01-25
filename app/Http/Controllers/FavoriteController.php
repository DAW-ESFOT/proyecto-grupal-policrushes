<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Favorite;

class FavoriteController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', Favorite::class);
        return Favorite::all();
    }
    public function show(Favorite $favorite)
    {
        $this->authorize('view', $favorite);
        return $favorite;
    }
    public function store(Request $request)
    {
        $this->authorize('create', Favorite::class);
        $favorite = Favorite::create($request->all());
        return response()->json($favorite, 201);
    }
    public function update(Request $request, Favorite $favorite)
    {
        $this->authorize('update', $favorite);
        $favorite->update($request->all());
        return response()->json($favorite, 200);
    }
    public function delete(Favorite $favorite)
    {
        $this->authorize('delete', $favorite);
        $favorite->delete();
        return response()->json(null, 204);
    }
}
