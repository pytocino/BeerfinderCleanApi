<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NotificationResource extends JsonResource
{
    /**
     * Transformar el recurso en un array.
     *
     * @param  Request  $request
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        $data = $this->data;
        
        return [
            'id' => $this->id,
            'type' => $data['type'] ?? 'unknown',
            'message' => $data['message'] ?? '',
            'is_read' => !is_null($this->read_at),
            'read_at' => $this->read_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'user' => $this->when(
                isset($data['user_id']),
                function () use ($data) {
                    $user = \App\Models\User::find($data['user_id']);
                    return $user ? [
                        'id' => $user->id,
                        'name' => $user->name,
                        'username' => $user->username,
                        'profile_picture' => $user->profile_picture,
                    ] : null;
                }
            ),
            'post' => $this->when(
                isset($data['post_id']),
                function () use ($data) {
                    $post = \App\Models\Post::find($data['post_id']);
                    return $post ? [
                        'id' => $post->id,
                        'content' => strlen($post->content) > 50 ? 
                            substr($post->content, 0, 50) . '...' : 
                            $post->content,
                        'photo_url' => $post->photo_url,
                    ] : null;
                }
            ),
            'comment' => $this->when(
                isset($data['comment_id']),
                function () use ($data) {
                    $comment = \App\Models\Comment::find($data['comment_id']);
                    return $comment ? [
                        'id' => $comment->id,
                        'content' => strlen($comment->content) > 30 ? 
                            substr($comment->content, 0, 30) . '...' : 
                            $comment->content,
                    ] : null;
                }
            ),
        ];
    }
}
