<?php

namespace App\Http\Controllers;

use App\Http\Resources\FavoriteCollection;
use App\Mail\NewFavorite;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Favorite;
use Illuminate\Support\Facades\Mail;
use App\Http\Resources\Favorite as FavoriteResource;

class FavoriteController extends Controller
{
    public function index(Favorite $favorite)
    {
        $this->authorize('viewAny', Favorite::class);
        $user = Auth::user();
        $favorites = $user->favorite1->merge($user->favorite2);
        return response()->json(new FavoriteCollection($favorites));
    }
    public function show(User $user, Favorite $favorite)
    {
        $favorite = $user->favorites1()->where('id', $favorite->id)->firstOrFail();
        $favorite = $user->favorites2()->where('id', $favorite->id)->firstOrFail();

        return response()->json(new FavoriteResource($favorite), 200);
    }
    public function store(Request $request)
    {
        $this->authorize('create', Favorite::class);

        $favorite = Favorite::create($request->all());

        Mail::to($favorite->user2->email)->send(new NewFavorite($favorite));


        return response()->json(new FavoriteResource($favorite), 201);
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
