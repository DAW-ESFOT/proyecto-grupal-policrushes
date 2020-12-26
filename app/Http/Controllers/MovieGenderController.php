<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MovieGender;

class MovieGenderController extends Controller
{
    public function index()
    {
        return MovieGender::all();
    }
    public function show(MovieGender $gender)
    {
        return $gender;
    }
    public function store(Request $request)
    {
        $gender = MovieGender::create($request->all());
        return response()->json($gender, 201);
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
