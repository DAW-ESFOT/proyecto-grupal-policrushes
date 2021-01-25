<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Match;

class MatchController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', Match::class);
        return Match::all();
    }
    public function show(Match $match)
    {
        $this->authorize('view', $match);
        return $match;
    }
    public function store(Request $request)
    {
        $this->authorize('create', Match::class);
        $match = Match::create($request->all());
        return response()->json($match, 201);
    }
    public function update(Request $request, Match $match)
    {
        $this->authorize('update', $match);
        $match->update($request->all());
        return response()->json($match, 200);
    }
    public function delete(Match $match)
    {
        $this->authorize('delete', $match);
        $match->delete();
        return response()->json(null, 204);
    }

}
