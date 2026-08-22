<?php

declare(strict_types=1);

namespace App\Domains\Chatbot\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

final class ChatbotFeedback extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'message_id',
        'type',
        'comment',
    ];

    protected $table = 'chatbot_feedback';

    public function message(): BelongsTo
    {
        return $this->belongsTo(ChatbotMessage::class, 'message_id');
    }
}
