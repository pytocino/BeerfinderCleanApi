<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Message extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * La tabla asociada con el modelo.
     *
     * @var string
     */
    protected $table = 'user_messages';

    /**
     * Los atributos que son asignables masivamente.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'conversation_id',
        'user_id',
        'content',
        'attachments',
        'reply_to',
        'is_edited',
        'read_at',
    ];

    /**
     * Los atributos que deben ser convertidos a tipos nativos.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'attachments' => 'array',
        'is_edited' => 'boolean',
        'read_at' => 'datetime',
    ];

    /**
     * Obtiene la conversación a la que pertenece el mensaje.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function conversation(): BelongsTo
    {
        return $this->belongsTo(Conversation::class);
    }

    /**
     * Obtiene el usuario que envió el mensaje.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Obtiene el mensaje al que este mensaje responde.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function replyToMessage(): BelongsTo
    {
        return $this->belongsTo(Message::class, 'reply_to');
    }

    /**
     * Obtiene las respuestas a este mensaje.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function replies(): HasMany
    {
        return $this->hasMany(Message::class, 'reply_to');
    }

    /**
     * Verifica si el mensaje ha sido editado.
     *
     * @return bool
     */
    public function isEdited(): bool
    {
        return $this->is_edited;
    }

    /**
     * Verifica si el mensaje ha sido leído.
     *
     * @return bool
     */
    public function isRead(): bool
    {
        return $this->read_at !== null;
    }

    /**
     * Verifica si el mensaje tiene adjuntos.
     *
     * @return bool
     */
    public function hasAttachments(): bool
    {
        return !empty($this->attachments);
    }

    /**
     * Verifica si el mensaje es una respuesta a otro.
     *
     * @return bool
     */
    public function isReply(): bool
    {
        return $this->reply_to !== null;
    }

    /**
     * Marca el mensaje como leído.
     *
     * @return bool
     */
    public function markAsRead(): bool
    {
        if ($this->isRead()) {
            return true;
        }

        return $this->update(['read_at' => now()]);
    }

    /**
     * Edita el contenido del mensaje.
     *
     * @param string $content
     * @return bool
     */
    public function edit(string $content): bool
    {
        return $this->update([
            'content' => $content,
            'is_edited' => true
        ]);
    }

    /**
     * Añade archivos adjuntos al mensaje.
     *
     * @param array $newAttachments
     * @return bool
     */
    public function addAttachments(array $newAttachments): bool
    {
        $currentAttachments = $this->attachments ?: [];
        $attachments = array_merge($currentAttachments, $newAttachments);

        return $this->update(['attachments' => $attachments]);
    }

    /**
     * Elimina un archivo adjunto específico.
     *
     * @param string $attachmentUrl
     * @return bool
     */
    public function removeAttachment(string $attachmentUrl): bool
    {
        if (!$this->hasAttachments()) {
            return false;
        }

        $attachments = array_filter($this->attachments, function ($attachment) use ($attachmentUrl) {
            return $attachment !== $attachmentUrl;
        });

        return $this->update(['attachments' => array_values($attachments)]);
    }

    /**
     * Después de enviar un mensaje, actualiza la conversación.
     */
    public static function booted()
    {
        static::created(function ($message) {
            $message->conversation->updateLastMessageTime();

            // Asegurarse de que los participantes estén al día
            ConversationUser::where('conversation_id', $message->conversation_id)
                ->where('user_id', $message->user_id)
                ->update(['last_read_at' => now()]);
        });
    }

    /**
     * Obtiene la URL de vista previa del primer adjunto (si es una imagen).
     *
     * @return string|null
     */
    public function getFirstImagePreview(): ?string
    {
        if (!$this->hasAttachments()) {
            return null;
        }

        foreach ($this->attachments as $attachment) {
            $extension = pathinfo($attachment, PATHINFO_EXTENSION);
            $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

            if (in_array(strtolower($extension), $imageExtensions)) {
                return $attachment;
            }
        }

        return null;
    }

    /**
     * Obtiene los adjuntos clasificados por tipo.
     *
     * @return array
     */
    public function getAttachmentsByType(): array
    {
        if (!$this->hasAttachments()) {
            return [];
        }

        $types = [
            'images' => [],
            'documents' => [],
            'videos' => [],
            'audio' => [],
            'other' => []
        ];

        foreach ($this->attachments as $attachment) {
            $extension = strtolower(pathinfo($attachment, PATHINFO_EXTENSION));

            if (in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                $types['images'][] = $attachment;
            } elseif (in_array($extension, ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'txt'])) {
                $types['documents'][] = $attachment;
            } elseif (in_array($extension, ['mp4', 'avi', 'mov', 'wmv'])) {
                $types['videos'][] = $attachment;
            } elseif (in_array($extension, ['mp3', 'wav', 'ogg'])) {
                $types['audio'][] = $attachment;
            } else {
                $types['other'][] = $attachment;
            }
        }

        return $types;
    }

    /**
     * Scope para filtrar por conversación.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int $conversationId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeInConversation($query, int $conversationId)
    {
        return $query->where('conversation_id', $conversationId);
    }

    /**
     * Scope para filtrar por usuario (remitente).
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int $userId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFromUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope para filtrar mensajes con adjuntos.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithAttachments($query)
    {
        return $query->whereNotNull('attachments')
            ->whereRaw("JSON_LENGTH(attachments) > 0");
    }

    /**
     * Scope para filtrar mensajes no leídos.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeUnread($query)
    {
        return $query->whereNull('read_at');
    }

    /**
     * Scope para filtrar respuestas a un mensaje específico.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int $messageId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeRepliesTo($query, int $messageId)
    {
        return $query->where('reply_to', $messageId);
    }

    /**
     * Scope para filtrar mensajes raíz (no son respuestas).
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeRootMessages($query)
    {
        return $query->whereNull('reply_to');
    }
}
