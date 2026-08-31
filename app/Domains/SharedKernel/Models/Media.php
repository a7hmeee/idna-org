<?php

declare(strict_types=1);

namespace App\Domains\SharedKernel\Models;

use App\Domains\Announcements\Models\Announcement;
use App\Domains\Department\Models\Department;
use App\Domains\Homepage\Models\HomepageSetting;
use App\Domains\Homepage\Models\HomepageSlide;
use App\Domains\Municipality\Models\CouncilMember;
use App\Domains\News\Models\NewsItem;
use App\Domains\Projects\Models\Project;
use App\Domains\PublicFacilities\Models\Facility;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;

/**
 * @property int $id
 * @property int $mediable_id
 * @property string $mediable_type
 * @property string $collection
 * @property string $disk
 * @property string $path
 * @property string|null $mime_type
 * @property int|null $size
 * @property int|null $width
 * @property int|null $height
 * @property string|null $title
 * @property string|null $alt
 * @property int $display_order
 * @property bool $is_active
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class Media extends Model
{
    use HasFactory;

    protected $fillable = [
        'mediable_id',
        'mediable_type',
        'collection',
        'disk',
        'path',
        'mime_type',
        'size',
        'width',
        'height',
        'title',
        'alt',
        'display_order',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'size' => 'integer',
            'width' => 'integer',
            'height' => 'integer',
            'display_order' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    public function mediable(): MorphTo
    {
        return $this->morphTo();
    }

    public function getUrlAttribute(): string
    {
        return asset('storage/'.$this->path);
    }

    public function getFormattedSizeAttribute(): string
    {
        if (! $this->size) {
            return '—';
        }

        if ($this->size >= 1048576) {
            return number_format($this->size / 1048576, 1).' م.ب';
        }

        return number_format($this->size / 1024, 1).' ك.ب';
    }

    public function getFormattedDimensionsAttribute(): string
    {
        if (! $this->width || ! $this->height) {
            return '—';
        }

        return $this->width.' × '.$this->height;
    }

    public function isImage(): bool
    {
        return str_starts_with($this->mime_type ?? '', 'image/') && $this->mime_type !== 'image/svg+xml';
    }

    public function fileExists(): bool
    {
        return Storage::disk($this->disk)->exists($this->path);
    }

    /**
     * Get all places where this media file is used.
     *
     * @return array<int, array{model: string, record_id: int, field: string, context: string}>
     */
    public function getUsageLocations(): array
    {
        $locations = [];
        $path = $this->path;

        // Check Homepage Slides
        $slides = HomepageSlide::where('image_path', $path)
            ->orWhere('mobile_image_path', $path)
            ->get();
        foreach ($slides as $slide) {
            $field = $slide->image_path === $path ? 'image_path' : 'mobile_image_path';
            $locations[] = [
                'model' => 'HomepageSlide',
                'record_id' => $slide->id,
                'field' => $field,
                'context' => 'Home Page → Hero Carousel → Slide #'.$slide->id,
            ];
        }

        // Check News Items
        $news = NewsItem::where('cover_image_path', $path)
            ->orWhere('mobile_image_path', $path)
            ->get();
        foreach ($news as $item) {
            $field = $item->cover_image_path === $path ? 'cover_image_path' : 'mobile_image_path';
            $locations[] = [
                'model' => 'NewsItem',
                'record_id' => $item->id,
                'field' => $field,
                'context' => 'News → '.$item->title,
            ];
        }

        // Check Announcements
        $announcements = Announcement::where('desktop_image_path', $path)
            ->orWhere('mobile_image_path', $path)
            ->get();
        foreach ($announcements as $announcement) {
            $field = $announcement->desktop_image_path === $path ? 'desktop_image_path' : 'mobile_image_path';
            $locations[] = [
                'model' => 'Announcement',
                'record_id' => $announcement->id,
                'field' => $field,
                'context' => 'Announcements → '.$announcement->title,
            ];
        }

        // Check Projects
        $projects = Project::where('cover_image_path', $path)->get();
        foreach ($projects as $project) {
            $locations[] = [
                'model' => 'Project',
                'record_id' => $project->id,
                'field' => 'cover_image_path',
                'context' => 'Projects → '.$project->title,
            ];
        }

        // Check Projects Gallery (JSON)
        $allProjects = Project::whereNotNull('gallery')->get();
        foreach ($allProjects as $project) {
            $gallery = $project->gallery ?? [];
            if (in_array($path, $gallery)) {
                $locations[] = [
                    'model' => 'Project',
                    'record_id' => $project->id,
                    'field' => 'gallery',
                    'context' => 'Projects → '.$project->title.' → Gallery',
                ];
            }
        }

        // Check Council Members
        $members = CouncilMember::where('photo_path', $path)->get();
        foreach ($members as $member) {
            $locations[] = [
                'model' => 'CouncilMember',
                'record_id' => $member->id,
                'field' => 'photo_path',
                'context' => 'Council → '.$member->name,
            ];
        }

        // Check Departments
        $departments = Department::where('cover_image_path', $path)->get();
        foreach ($departments as $department) {
            $locations[] = [
                'model' => 'Department',
                'record_id' => $department->id,
                'field' => 'cover_image_path',
                'context' => 'Departments → '.$department->name,
            ];
        }

        // Check Facilities
        $facilities = Facility::where('cover_image_path', $path)->get();
        foreach ($facilities as $facility) {
            $locations[] = [
                'model' => 'Facility',
                'record_id' => $facility->id,
                'field' => 'cover_image_path',
                'context' => 'Facilities → '.$facility->name,
            ];
        }

        // Check Facilities Gallery (JSON)
        $allFacilities = Facility::whereNotNull('gallery')->get();
        foreach ($allFacilities as $facility) {
            $gallery = $facility->gallery ?? [];
            if (in_array($path, $gallery)) {
                $locations[] = [
                    'model' => 'Facility',
                    'record_id' => $facility->id,
                    'field' => 'gallery',
                    'context' => 'Facilities → '.$facility->name.' → Gallery',
                ];
            }
        }

        // Check Homepage Settings (mayor image)
        $settings = HomepageSetting::where('mayor_image_path', $path)->get();
        foreach ($settings as $setting) {
            $locations[] = [
                'model' => 'HomepageSetting',
                'record_id' => $setting->id,
                'field' => 'mayor_image_path',
                'context' => 'Homepage Settings → Mayor Image',
            ];
        }

        return $locations;
    }

    public function isUsed(): bool
    {
        return count($this->getUsageLocations()) > 0;
    }

    public function getUsageCount(): int
    {
        return count($this->getUsageLocations());
    }
}
