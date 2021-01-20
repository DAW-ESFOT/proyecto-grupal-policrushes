<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Chat;
use App\Http\Resources\Chat as ResourceChat;

class ChatController extends Controller
{
    public function index()
    {
        return Chat::all();
    }
    public function show(Chat $chat)
    {
        return $chat;
    }
    public function store(Request $request)
    {
        $chat = Chat::create($request->all());
        return response()->json($chat, 201);
    }
    public function update(Request $request, Chat $chat)
    {
        $chat->update($request->all());
        return response()->json($chat, 200);
    }
    public function delete(Chat $chat)
    {
        $chat->delete();
        return response()->json(null, 204);
    }
}
