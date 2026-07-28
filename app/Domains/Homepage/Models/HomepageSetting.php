<?php

declare(strict_types=1);

namespace App\Domains\Homepage\Models;

use App\Domains\Authentication\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property string|null $site_title
 * @property string|null $site_subtitle
 * @property string|null $portal_url
 * @property string|null $primary_button_text
 * @property string|null $secondary_button_text
 * @property string|null $secondary_button_url
 * @property string|null $welcome_title
 * @property string|null $welcome_description
 * @property string|null $mayor_message_title
 * @property string|null $mayor_message
 * @property string|null $mayor_image_path
 * @property bool $show_mayor_message
 * @property string|null $contact_cta_title
 * @property string|null $contact_cta_description
 * @property string|null $contact_cta_button_text
 * @property string|null $contact_cta_button_url
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
final class HomepageSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'site_title',
        'site_subtitle',
        'portal_url',
        'primary_button_text',
        'secondary_button_text',
        'secondary_button_url',
        'welcome_title',
        'welcome_description',
        'mayor_message_title',
        'mayor_message',
        'mayor_image_path',
        'show_mayor_message',
        'contact_cta_title',
        'contact_cta_description',
        'contact_cta_button_text',
        'contact_cta_button_url',
        'created_by',
        'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'show_mayor_message' => 'boolean',
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

    public function getMayorImageUrlAttribute(): ?string
    {
        if (!$this->mayor_image_path) {
            return null;
        }

        if (\Illuminate\Support\Facades\Storage::disk('public')->exists($this->mayor_image_path)) {
            return asset('storage/' . $this->mayor_image_path);
        }

        return null;
    }
}
