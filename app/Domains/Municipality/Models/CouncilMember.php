<?php

declare(strict_types=1);

namespace App\Domains\Municipality\Models;

use App\Domains\Authentication\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * @property int $id
 * @property string $full_name
 * @property string $slug
 * @property string|null $national_number
 * @property string $position
 * @property string|null $qualification
 * @property string|null $profession
 * @property string|null $bio
 * @property string|null $photo_path
 * @property string|null $phone
 * @property string|null $mobile
 * @property string|null $email
 * @property string|null $address
 * @property string|null $facebook
 * @property string|null $twitter
 * @property string|null $linkedin
 * @property string $term_start
 * @property string|null $term_end
 * @property int|null $years_of_experience
 * @property string|null $committee
 * @property string $status
 * @property int $display_order
 * @property bool $is_public
 * @property bool $is_featured
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 */
final class CouncilMember extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'full_name',
        'slug',
        'national_number',
        'position',
        'qualification',
        'profession',
        'bio',
        'photo_path',
        'phone',
        'mobile',
        'email',
        'address',
        'facebook',
        'twitter',
        'linkedin',
        'term_start',
        'term_end',
        'years_of_experience',
        'committee',
        'status',
        'display_order',
        'is_public',
        'is_featured',
        'created_by',
        'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'is_public' => 'boolean',
            'is_featured' => 'boolean',
            'display_order' => 'integer',
            'years_of_experience' => 'integer',
            'term_start' => 'date:Y-m-d',
            'term_end' => 'date:Y-m-d',
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

    public function getPhotoUrlAttribute(): ?string
    {
        if (! $this->photo_path) {
            return null;
        }

        if (Storage::disk('public')->exists($this->photo_path)) {
            return asset('storage/'.$this->photo_path);
        }

        return null;
    }

    protected static function booted(): void
    {
        self::creating(function (CouncilMember $member): void {
            if (empty($member->slug)) {
                $base = Str::slug($member->full_name);
                $slug = $base;
                $counter = 1;

                while (static::where('slug', $slug)->exists()) {
                    $slug = $base.'-'.$counter++;
                }

                $member->slug = $slug;
            }

            if (empty($member->display_order)) {
                $member->display_order = static::max('display_order') + 1;
            }
        });
    }
}
