<?php

declare(strict_types=1);

namespace App\Domains\CitizenWorkflows\Models;

use Illuminate\Database\Eloquent\Model;

class WorkflowDraft extends Model
{
    protected $table = 'workflow_drafts';

    protected $fillable = [
        'workflow_type',
        'session_id',
        'citizen_user_id',
        'current_step',
        'answers',
        'validation_errors',
        'status',
        'expires_at',
        'completed_at',
        'cancelled_at',
        'final_entity_type',
        'final_entity_id',
        'tracking_number',
        'metadata',
    ];

    protected function casts(): array
    {
        return [
            'answers' => 'array',
            'validation_errors' => 'array',
            'metadata' => 'array',
            'expires_at' => 'datetime',
            'completed_at' => 'datetime',
            'cancelled_at' => 'datetime',
        ];
    }

    public function __get($key)
    {
        if ($key === 'data') {
            return $this->attributes['answers'] ?? null;
        }

        return parent::__get($key);
    }

    public function __set($key, $value): void
    {
        if ($key === 'data') {
            $this->attributes['answers'] = is_array($value) ? json_encode($value) : $value;
        }

        parent::__set($key, $value);
    }
}
