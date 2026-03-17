<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SupportTicket extends Model
{
    protected $fillable = [
        'user_id',
        'support_type',
        'subject',
        'description',
        'status',
        'priority',
        'contact_method',
        'whatsapp_number',
        'response_at',
        'resolved_at',
    ];

    protected $casts = [
        'response_at' => 'datetime',
        'resolved_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function messages()
    {
        return $this->hasMany(SupportMessage::class);
    }
}
