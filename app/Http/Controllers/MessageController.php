<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Message;

class MessageController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', Message::class);
        return Message::all();
    }
    public function show(Message $message)
    {
        $this->authorize('view', $message);
        return $message;
    }
    public function store(Request $request)
    {
        $this->authorize('create', Message::class);
        $message = Message::create($request->all());
        return response()->json($message, 201);
    }
    public function update(Request $request, Message $message)
    {
        $this->authorize('update', $message);
        $message->update($request->all());
        return response()->json($message, 200);
    }
    public function delete(Message $message)
    {
        $this->authorize('delete', $message);
        $message->delete();
        return response()->json(null, 204);
    }

}
