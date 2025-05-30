<?php

namespace App\Http\Controllers\API\Social;

use App\Http\Controllers\Controller;
use App\Http\Resources\ConversationResource;
use App\Http\Resources\MessageResource;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Follow;

class ConversationController extends Controller
{
    // Listar todas las conversaciones del usuario autenticado (paginadas)
    public function index()
    {
        $user = auth()->user();
        $conversations = $user->conversations()
            ->with([
                'users',
                'messages' => function ($q) {
                    $q->latest()->limit(1)->with(['sender', 'receiver']);
                }
            ])
            ->latest('updated_at')
            ->paginate(15);

        return ConversationResource::collection($conversations);
    }

    // Crear una conversación (y enviar el primer mensaje)
    public function store(Request $request)
    {
        $request->validate([
            'participant_ids' => 'required|array|min:1',
            'participant_ids.*' => 'exists:users,id',
            'content' => 'required|string',
        ]);

        $authUser = auth()->user();

        // Verificar solo que los usuarios existan (eliminamos restricciones de privacidad)
        foreach ($request->participant_ids as $participantId) {
            User::findOrFail($participantId);
        }

        // Incluye al usuario autenticado como participante
        $participantIds = array_unique(array_merge($request->participant_ids, [$authUser->id]));
        sort($participantIds);

        // Buscar una conversación con exactamente estos participantes
        $conversation = Conversation::whereHas('users', function ($q) use ($participantIds) {
            $q->whereIn('user_id', $participantIds);
        })
            ->withCount(['users'])
            ->get()
            ->first(function ($conv) use ($participantIds) {
                $convIds = $conv->users->pluck('id')->sort()->values()->toArray();
                return $convIds === $participantIds;
            });

        if (!$conversation) {
            $conversation = Conversation::create();
            $conversation->users()->attach($participantIds);
        }

        // Crea el primer mensaje
        $receiverId = collect($participantIds)->first(fn($id) => $id !== $authUser->id);
        $message = $conversation->messages()->create([
            'sender_id' => $authUser->id,
            'receiver_id' => $receiverId,
            'content' => $request->content,
            'is_read' => false,
        ]);

        return response()->json([
            'conversation' => new ConversationResource($conversation->fresh(['users', 'messages.sender', 'messages.receiver'])),
            'message' => new MessageResource($message->load(['sender', 'receiver'])),
        ]);
    }

    // Enviar mensaje a una conversación existente
    public function sendMessage(Request $request, $id)
    {
        $request->validate([
            'content' => 'required|string',
        ]);

        $conversation = Conversation::with('users')->findOrFail($id);

        if (!$conversation->users->contains(auth()->id())) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        // Para 1 a 1, busca el otro participante
        $receiverId = $conversation->users()->where('user_id', '!=', auth()->id())->first()->id ?? null;

        $message = $conversation->messages()->create([
            'sender_id' => auth()->user()->id,
            'receiver_id' => $receiverId,
            'content' => $request->content,
            'is_read' => false,
        ]);

        return new MessageResource($message->load(['sender', 'receiver']));
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
            ->where('receiver_id', '=', auth()->id())
            ->where('is_read', '=', false)
            ->update(['is_read' => true]);

        return response()->json(['message' => 'Mensajes marcados como leídos.']);
    }
    public function show($id)
    {
        // Buscar la conversación
        $conversation = Conversation::with('users')->find($id);

        // Obtener los mensajes de la conversación, ordenados por fecha
        $messages = Message::with(['sender', 'receiver'])
            ->where('conversation_id', '=', $conversation->id)
            ->orderBy('created_at')
            ->get();

        return response()->json([
            'conversation' => new ConversationResource($conversation),
            'messages' => MessageResource::collection($messages),
        ]);
    }
}
