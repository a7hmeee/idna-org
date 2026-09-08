<?php

declare(strict_types=1);

namespace App\Domains\Notifications\Models;

use App\Domains\Authentication\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Notifications\Notifiable;

final class DashboardNotification extends Model
{
    use Notifiable;

    protected $fillable = [
        'user_id',
        'title',
        'message',
        'type',
        'category',
        'action_url',
        'action_label',
        'notifiable_type',
        'notifiable_id',
        'is_read',
        'read_at',
    ];

    protected function casts(): array
    {
        return [
            'is_read' => 'boolean',
            'read_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function notifiable()
    {
        return $this->morphTo();
    }

    public function markAsRead(): void
    {
        $this->update([
            'is_read' => true,
            'read_at' => now(),
        ]);
    }

    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    public function scopeForUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeLatestFirst($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    public static function getUnreadCount(int $userId): int
    {
        return self::where('user_id', $userId)->where('is_read', false)->count();
    }

    public static function markAllAsRead(int $userId): void
    {
        self::where('user_id', $userId)
            ->where('is_read', false)
            ->update(['is_read' => true, 'read_at' => now()]);
    }
}
