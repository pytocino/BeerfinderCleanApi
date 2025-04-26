<?php

namespace App\Http\Controllers\API;

use App\Events\MessageSent;
use App\Http\Controllers\Controller;
use App\Http\Resources\MessageResource;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function index(User $user)
    {
        // Reutilizar la lógica del método conversation
        $messages = Message::where(function ($q) use ($user) {
            $q->where('sender_id', auth()->id())
                ->where('receiver_id', $user->id);
        })->orWhere(function ($q) use ($user) {
            $q->where('sender_id', $user->id)
                ->where('receiver_id', auth()->id());
        })->orderBy('created_at')->get();

        // Marcar mensajes como leídos automáticamente
        $unreadMessages = $messages->where('receiver_id', auth()->id())
            ->where('is_read', false);

        if ($unreadMessages->count() > 0) {
            Message::whereIn('id', $unreadMessages->pluck('id'))->update(['is_read' => true]);
        }

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

        // Marcar mensajes como leídos automáticamente
        $unreadMessages = $messages->where('receiver_id', auth()->id())
            ->where('is_read', false);

        if ($unreadMessages->count() > 0) {
            Message::whereIn('id', $unreadMessages->pluck('id'))->update(['is_read' => true]);
        }

        return MessageResource::collection($messages);
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

        return new MessageResource($message);
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

    /**
     * Obtener todas las conversaciones del usuario autenticado
     */
    public function conversations()
    {
        $currentUserId = auth()->id();

        // Obtener todos los usuarios con los que ha intercambiado mensajes
        $userIds = Message::where('receiver_id', $currentUserId)
            ->orWhere('sender_id', $currentUserId)
            ->get(['sender_id', 'receiver_id'])
            ->map(function ($message) use ($currentUserId) {
                return $message->sender_id == $currentUserId
                    ? $message->receiver_id
                    : $message->sender_id;
            })
            ->unique()
            ->values();

        $conversations = [];

        foreach ($userIds as $userId) {
            $user = User::find($userId);

            // Obtener el último mensaje intercambiado con este usuario
            $lastMessage = Message::where(function ($query) use ($currentUserId, $userId) {
                $query->where('sender_id', $currentUserId)
                    ->where('receiver_id', $userId);
            })->orWhere(function ($query) use ($currentUserId, $userId) {
                $query->where('sender_id', $userId)
                    ->where('receiver_id', $currentUserId);
            })
                ->latest()
                ->first();

            // Contar mensajes no leídos
            $unreadCount = Message::where('sender_id', $userId)
                ->where('receiver_id', $currentUserId)
                ->where('is_read', false)
                ->count();

            $conversations[] = [
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'username' => $user->username,
                    'profile_picture' => $user->profile_picture,
                ],
                'last_message' => new MessageResource($lastMessage),
                'unread_count' => $unreadCount
            ];
        }

        // Ordenar por fecha del último mensaje (más reciente primero)
        usort($conversations, function ($a, $b) {
            $timeA = strtotime($a['last_message']->created_at);
            $timeB = strtotime($b['last_message']->created_at);
            return $timeB - $timeA;
        });

        return response()->json($conversations);
    }
}
