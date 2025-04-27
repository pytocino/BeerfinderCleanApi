<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\ConversationResource;
use App\Http\Resources\MessageResource;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;

class ConversationController extends Controller
{
    // Listar todas las conversaciones del usuario autenticado
    public function index()
    {
        $user = auth()->user();
        $conversations = $user->conversations()->with(['users', 'messages' => function ($q) {
            $q->latest()->limit(1);
        }])->get();

        return ConversationResource::collection($conversations);
    }

    // Ver una conversación específica (con mensajes y participantes)
    public function show($id)
    {
        $conversation = Conversation::with(['users', 'messages.sender', 'messages.receiver'])
            ->findOrFail($id);

        // Verifica que el usuario sea participante
        if (!$conversation->users->contains(auth()->id())) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        return new ConversationResource($conversation->load(['messages' => function ($q) {
            $q->orderBy('created_at');
        }]));
    }

    // Crear una conversación (y enviar el primer mensaje)
    public function store(Request $request)
    {
        $request->validate([
            'participant_ids' => 'required|array|min:1',
            'participant_ids.*' => 'exists:users,id',
            'content' => 'required|string',
        ]);

        // Incluye al usuario autenticado como participante
        $participantIds = array_unique(array_merge($request->participant_ids, [auth()->id()]));

        // Busca si ya existe una conversación con exactamente estos participantes
        $conversation = Conversation::whereHas('users', function ($q) use ($participantIds) {
            $q->whereIn('user_id', $participantIds);
        }, '=', count($participantIds))->first();

        if (!$conversation) {
            $conversation = Conversation::create();
            $conversation->users()->attach($participantIds);
        }

        // Crea el primer mensaje
        $message = $conversation->messages()->create([
            'sender_id' => auth()->id(),
            'receiver_id' => $request->participant_ids[0], // Para 1 a 1, el primero
            'content' => $request->content,
        ]);

        return response()->json([
            'conversation' => new ConversationResource($conversation),
            'message' => new MessageResource($message),
        ]);
    }

    // Enviar mensaje a una conversación existente
    public function sendMessage(Request $request, $id)
    {
        $request->validate([
            'content' => 'required|string',
        ]);

        $conversation = Conversation::findOrFail($id);

        if (!$conversation->users->contains(auth()->id())) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        // Para 1 a 1, busca el otro participante
        $receiverId = $conversation->users()->where('user_id', '!=', auth()->id())->first()->id ?? null;

        $message = $conversation->messages()->create([
            'sender_id' => auth()->id(),
            'receiver_id' => $receiverId,
            'content' => $request->content,
        ]);

        return new MessageResource($message);
    }

    // Añadir participantes a una conversación (opcional, para chats grupales)
    public function addParticipants(Request $request, $id)
    {
        $request->validate([
            'participant_ids' => 'required|array|min:1',
            'participant_ids.*' => 'exists:users,id',
        ]);

        $conversation = Conversation::findOrFail($id);

        if (!$conversation->users->contains(auth()->id())) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $conversation->users()->syncWithoutDetaching($request->participant_ids);

        return new ConversationResource($conversation->fresh('users'));
    }

    // Marcar todos los mensajes como leídos en una conversación
    public function markAllAsRead($id)
    {
        $conversation = Conversation::findOrFail($id);

        if (!$conversation->users->contains(auth()->id())) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $conversation->messages()
            ->where('receiver_id', auth()->id())
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return response()->json(['message' => 'Mensajes marcados como leídos.']);
    }
}
