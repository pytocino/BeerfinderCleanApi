<?php

namespace App\Http\Controllers\API;

use App\Events\MessageSent;
use App\Http\Controllers\Controller;
use App\Http\Resources\MessageResource;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;

// app/Http/Controllers/MessageController.php
class MessageController extends Controller
{
    public function index()
    {
        // Obtener todos los mensajes del usuario autenticado
        $messages = Message::where('receiver_id', auth()->id())
            ->orWhere('sender_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->get();

        return MessageResource::collection($messages);
    }

    // Obtener la conversación entre dos usuarios
    public function conversation(User $user)
    {
        $messages = Message::where(function ($q) use ($user) {
            $q->where('sender_id', auth()->id())
                ->where('receiver_id', $user->id);
        })->orWhere(function ($q) use ($user) {
            $q->where('sender_id', $user->id)
                ->where('receiver_id', auth()->id());
        })->orderBy('created_at')->get();

        return response()->json($messages);
    }

    // Enviar mensaje
    public function store(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'content' => 'required|string',
        ]);

        $message = Message::create([
            'sender_id' => auth()->id(),
            'receiver_id' => $request->receiver_id,
            'content' => $request->content,
        ]);

        broadcast(new MessageSent($message))->toOthers();


        return response()->json($message, 201);
    }

    public function markAsRead(Message $message)
    {
        if ($message->receiver_id !== auth()->id()) {
            return response()->json(['message' => 'No autorizado.'], 403);
        }

        $message->is_read = true;
        $message->save();

        return response()->json(['message' => 'Mensaje marcado como leído.']);
    }
}
