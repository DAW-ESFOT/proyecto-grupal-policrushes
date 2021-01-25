<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Message;

class MessageController extends Controller
{
    public function index()
    {
        return Message::all();
    }
    public function show(Message $message)
    {
        return $message;
    }
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'date' => 'timestamp',
            'content' => 'required|string|max:255',
        ]);
        $message = Message::create($request->all());
        return response()->json($message, 201);
    }
    public function update(Request $request, Message $message)
    {
        $message->update($request->all());
        return response()->json($message, 200);
    }
    public function delete(Message $message)
    {
        $message->delete();
        return response()->json(null, 204);
    }

}
