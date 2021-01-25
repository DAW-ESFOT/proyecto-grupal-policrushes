<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MovieGender;

class MovieGenderController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', MovieGender::class);
        return MovieGender::all();
    }
    public function show(MovieGender $gender)
    {
        $this->authorize('view', $gender);
        return $gender;
    }
    public function store(Request $request)
    {
        $this->authorize('create', MovieGender::class);
        $gender = MovieGender::create($request->all());
        return response()->json($gender, 201);
    }
    public function update(Request $request, MovieGender $gender)
    {
        $this->authorize('update', $gender);
        $gender->update($request->all());
        return response()->json($gender, 200);
    }
    public function delete(MovieGender $gender)
    {
        $this->authorize('delete', $gender);
        $gender->delete();
        return response()->json(null, 204);
    }

}
