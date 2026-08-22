<?php

declare(strict_types=1);

namespace App\Domains\Chatbot\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

final class ChatbotServiceAlias extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'alias',
        'service_key',
        'description',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'metadata' => 'array',
        ];
    }

    protected $table = 'chatbot_service_aliases';
}
