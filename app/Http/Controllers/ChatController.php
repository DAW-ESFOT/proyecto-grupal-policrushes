<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Chat;

class ChatController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', Chat::class);
        return Chat::all();
    }
    public function show(Chat $chat)
    {
        $this->authorize('view', $chat);
        return $chat;
    }
    public function store(Request $request)
    {
        $this->authorize('create', Chat::class);
        $chat = Chat::create($request->all());
        return response()->json($chat, 201);
    }
    public function update(Request $request, Chat $chat)
    {
        $this->authorize('update', $chat);
        $chat->update($request->all());
        return response()->json($chat, 200);
    }
    public function delete(Chat $chat)
    {
        $this->authorize('delete', $chat);
        $chat->delete();
        return response()->json(null, 204);
    }
}
