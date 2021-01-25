<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Favorite;
use App\Models\User;
use App\Http\Resources\Favorite as FavoriteResource;

class FavoriteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param User $user
     * @return void
     */
    public function index(User $user)
    {
        return response()->json(FavoriteResource::collection($user->favorites->sortByDesc('created_at')), 200);
    }
    public function show(Favorite $favorite, User $user)
    {
        $favorite = $user->comments()->where('id', $favorite->id)->firstOrFail();
        return response()->json(new FavoriteResource($favorite), 200);
    }
    public function store(Request $request)
    {
        $favorite = Favorite::create($request->all());
        return response()->json($favorite, 201);
    }
    public function update(Request $request, Favorite $favorite)
    {
        $favorite->update($request->all());
        return response()->json($favorite, 200);
    }
    public function delete(Favorite $favorite)
    {
        $favorite->delete();
        return response()->json(null, 204);
    }
}
