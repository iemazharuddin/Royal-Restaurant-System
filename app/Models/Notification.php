<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $table = 'admin_notifications';

    protected $fillable = [
        'title',
        'message',
        'audience',
        'recipient_email',
        'type',
        'is_read',
        'link',
        'sent_at',
    ];

    protected function casts(): array
    {
        return [
            'is_read' => 'boolean',
            'sent_at' => 'datetime',
        ];
    }
}
