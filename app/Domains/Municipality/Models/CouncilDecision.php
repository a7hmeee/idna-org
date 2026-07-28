<?php

declare(strict_types=1);

namespace App\Domains\Municipality\Models;

use App\Domains\Authentication\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property string $decision_number
 * @property string $title
 * @property string|null $summary
 * @property string|null $content
 * @property string $type
 * @property string $status
 * @property string|null $decision_date
 * @property string|null $session_number
 * @property string|null $attachment_path
 * @property bool $is_public
 * @property int $sort_order
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property string|null $published_at
 * @property string|null $archived_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 */
final class CouncilDecision extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'decision_number',
        'title',
        'summary',
        'content',
        'type',
        'status',
        'decision_date',
        'session_number',
        'attachment_path',
        'is_public',
        'sort_order',
        'created_by',
        'updated_by',
        'published_at',
        'archived_at',
    ];

    protected function casts(): array
    {
        return [
            'is_public' => 'boolean',
            'sort_order' => 'integer',
            'decision_date' => 'date:Y-m-d',
            'published_at' => 'datetime',
            'archived_at' => 'datetime',
        ];
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
