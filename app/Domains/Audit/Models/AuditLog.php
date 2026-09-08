<?php

declare(strict_types=1);

namespace App\Domains\Audit\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class AuditLog extends Model
{
    protected $fillable = [
        'user_id',
        'user_name',
        'user_email',
        'action',
        'module',
        'entity_type',
        'entity_id',
        'old_values',
        'new_values',
        'changed_fields',
        'ip_address',
        'user_agent',
        'method',
        'url',
        'route_name',
        'status',
        'description',
        'metadata',
    ];

    protected function casts(): array
    {
        return [
            'old_values' => 'array',
            'new_values' => 'array',
            'changed_fields' => 'array',
            'metadata' => 'array',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeForModule($query, string $module)
    {
        return $query->where('module', $module);
    }

    public function scopeForAction($query, string $action)
    {
        return $query->where('action', $action);
    }

    public function scopeForEntity($query, string $entityType, $entityId)
    {
        return $query->where('entity_type', $entityType)->where('entity_id', $entityId);
    }

    public function scopeForUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeDateRange($query, string $from, string $to)
    {
        return $query->whereBetween('created_at', [$from, $to]);
    }

    public function scopeLatestFirst($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    private static array $sensitiveFields = [
        'password',
        'password_confirmation',
        'current_password',
        'new_password',
        'new_password_confirmation',
        'remember_token',
        'api_token',
        'secret',
        'token',
        'two_factor_secret',
    ];

    public static function redactSensitive(array $data): array
    {
        foreach (self::$sensitiveFields as $field) {
            if (array_key_exists($field, $data)) {
                $data[$field] = '[REDACTED]';
            }
        }

        return $data;
    }

    public static function log(array $params): self
    {
        $user = auth()->user();

        return self::create([
            'user_id' => $user?->id,
            'user_name' => $user?->name,
            'user_email' => $user?->email,
            'action' => $params['action'],
            'module' => $params['module'] ?? null,
            'entity_type' => $params['entity_type'] ?? null,
            'entity_id' => $params['entity_id'] ?? null,
            'old_values' => isset($params['old_values']) ? self::redactSensitive($params['old_values']) : null,
            'new_values' => isset($params['new_values']) ? self::redactSensitive($params['new_values']) : null,
            'changed_fields' => $params['changed_fields'] ?? null,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'method' => request()->method(),
            'url' => request()->fullUrl(),
            'route_name' => request()->route()?->getName(),
            'status' => $params['status'] ?? 'success',
            'description' => $params['description'] ?? null,
            'metadata' => $params['metadata'] ?? null,
        ]);
    }
}
