<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MusicGender;

class MusicGenderController extends Controller
{
    public function index()
    {
        return MusicGender::all();
    }
    public function show(MusicGender $gender)
    {
        return $gender;
    }
    public function store(Request $request)
    {
        $gender = MusicGender::create($request->all());
        return response()->json($gender, 201);
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
