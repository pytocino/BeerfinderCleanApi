<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'bio',
        'location',
        'birthdate',
        'website',
        'phone',
        'instagram',
        'twitter',
        'facebook',
        'private_profile',
        'allow_mentions',
        'email_notifications',
    ];

    protected $casts = [
        'birthdate' => 'date',
        'private_profile' => 'boolean',
        'allow_mentions' => 'boolean',
        'email_notifications' => 'boolean',
    ];

    /**
     * Relación con el usuario principal.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
