<?php

declare(strict_types=1);

namespace App\Domains\Chatbot\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

final class ChatbotModelVersion extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'version',
        'status',
        'path',
        'metadata',
    ];

    protected function casts(): array
    {
        return [
            'metadata' => 'array',
        ];
    }

    protected $table = 'chatbot_model_versions';
}
