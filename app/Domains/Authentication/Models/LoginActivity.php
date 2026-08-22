<?php

declare(strict_types=1);

namespace App\Domains\Authentication\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $user_id
 * @property string $ip_address
 * @property string $user_agent
 * @property string $event_type
 * @property bool $successful
 * @property string|null $failure_reason
 * @property string|null $session_id
 * @property Carbon|null $created_at
 */
class LoginActivity extends Model
{
    public const string EVENT_LOGIN = 'login';

    public const string EVENT_LOGOUT = 'logout';

    public const string EVENT_FAILED = 'failed';

    public const string EVENT_PASSWORD_CHANGE = 'password_change';

    public const string EVENT_PASSWORD_RESET = 'password_reset';

    public const string EVENT_LOCKOUT = 'lockout';

    public const UPDATED_AT = null;

    protected $fillable = [
        'user_id',
        'ip_address',
        'user_agent',
        'event_type',
        'successful',
        'failure_reason',
        'session_id',
    ];

    protected function casts(): array
    {
        return [
            'successful' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
