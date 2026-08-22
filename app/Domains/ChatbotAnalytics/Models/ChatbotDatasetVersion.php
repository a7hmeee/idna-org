<?php

declare(strict_types=1);

namespace App\Domains\ChatbotAnalytics\Models;

use App\Domains\Authentication\Models\User;
use App\Domains\Chatbot\Models\ChatbotModelVersion;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

final class ChatbotDatasetVersion extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'version_tag',
        'description',
        'fingerprint',
        'example_count',
        'intent_count',
        'intent_distribution',
        'created_by',
        'model_version_id',
        'is_baseline',
        'export_path',
    ];

    protected function casts(): array
    {
        return [
            'intent_distribution' => 'array',
            'is_baseline' => 'boolean',
            'example_count' => 'integer',
            'intent_count' => 'integer',
        ];
    }

    protected $table = 'chatbot_dataset_versions';

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function modelVersion(): BelongsTo
    {
        return $this->belongsTo(ChatbotModelVersion::class, 'model_version_id');
    }
}
