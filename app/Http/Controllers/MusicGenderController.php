<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MusicGender;

class MusicGenderController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', MusicGender::class);
        return MusicGender::all();
    }
    public function show(MusicGender $gender)
    {
        $this->authorize('view', $gender);
        return $gender;
    }
    public function store(Request $request)
    {
        $this->authorize('create', MusicGender::class);
        $gender = MusicGender::create($request->all());
        return response()->json($gender, 201);
    }
    public function update(Request $request, MusicGender $gender)
    {
        $this->authorize('update', $gender);
        $gender->update($request->all());
        return response()->json($gender, 200);
    }
    public function delete(MusicGender $gender)
    {
        $this->authorize('delete', $gender);
        $gender->delete();
        return response()->json(null, 204);
    }
}
