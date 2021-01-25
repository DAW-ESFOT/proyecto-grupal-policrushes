<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MovieGender;
use App\Http\Resources\MovieGender as MovieResource;
use App\Models\User;

class MovieGenderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param User $user
     * @return void
     */
    public function index(User $user)
    {
        return response()->json(MovieResource::collection($user->movie_genders->sortByDesc('created_at')), 200);
        //return MovieGender::all();
    }
    public function show(User $user, MovieGender $gender)
    {
        $gender = $user->comments()->where('id', $gender->id)->firstOrFail();
        return response()->json(new MovieResource($gender), 200);
    }
    public function store(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string'
        ]);

        $gender = $user->comments()->save(new MovieGender($request->all()));
        return response()->json(new MovieResource($gender), 201);
        //$gender = MovieGender::create($request->all());
        //return response()->json($gender, 201);
    }
    public function update(Request $request, MovieGender $gender)
    {
        $gender->update($request->all());
        return response()->json($gender, 200);
    }
    public function delete(MovieGender $gender)
    {
        $gender->delete();
        return response()->json(null, 204);
    }

}
