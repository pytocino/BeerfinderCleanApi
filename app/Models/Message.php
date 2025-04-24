<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// app/Models/Message.php
class Message extends Model
{
    protected $fillable = ['sender_id', 'receiver_id', 'content', 'is_read'];

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }
}
