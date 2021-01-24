<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Match;

class MatchController extends Controller
{
    public function index()
    {
        return Match::all();
    }
    public function show(Match $match)
    {
        return $match;
    }
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'date' => 'timestamp',
            'content' => 'required|string|max:255',
        ]);
        $match = Match::create($request->all());
        return response()->json($match, 201);
    }
    public function update(Request $request, Match $match)
    {
        $match->update($request->all());
        return response()->json($match, 200);
    }
    public function delete(Match $match)
    {
        $match->delete();
        return response()->json(null, 204);
    }

}
