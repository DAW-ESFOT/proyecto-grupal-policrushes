<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MusicGender;
use App\Models\User;
use App\Http\Resources\MusicGender as MusicResource;

class MusicGenderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param User $user
     * @return void
     */
    public function index(User $user)
    {
        return response()->json(MusicResource::collection($user->music_genders->sortByDesc('created_at')), 200);
        //return MovieGender::all();
    }
    public function show(User $user, MusicGender $gender)
    {
        $gender = $user->comments()->where('id', $gender->id)->firstOrFail();
        return response()->json(new MusicResource($gender), 200);;
    }
    public function store(User $user, Request $request)
    {
        $request->validate([
            'name' => 'required|string'
        ]);

        $gender = $user->comments()->save(new MusicGender($request->all()));
        return response()->json(new MusicResource($gender), 201);
    }
    public function update(Request $request, MusicGender $gender)
    {
        $gender->update($request->all());
        return response()->json($gender, 200);
    }
    public function delete(MusicGender $gender)
    {
        $gender->delete();
        return response()->json(null, 204);
    }
}
