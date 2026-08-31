<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Domains\Announcements\Models\Announcement;
use App\Domains\Department\Models\Department;
use App\Domains\ElectronicServices\Models\ServiceCategory;
use App\Domains\Homepage\Models\HomepageSetting;
use App\Domains\Homepage\Models\HomepageSlide;
use App\Domains\Municipality\Contracts\MunicipalityRepositoryInterface;
use App\Domains\Municipality\Models\CouncilMember;
use App\Domains\Municipality\Models\Municipality;
use App\Domains\News\Models\NewsItem;
use App\Domains\Projects\Models\Project;
use App\Domains\PublicFacilities\Models\Facility;
use App\Domains\SharedKernel\Enums\MediaCollection;
use App\Domains\SharedKernel\Models\Media;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

/**
 * Safely registers every existing image file (referenced by domain models or stored
 * in the municipality media library) as a Media record.
 *
 * - IDEMPOTENT: re-running never creates duplicates (deduped by file path).
 * - READ-ONLY under --dry-run (default): reports what WOULD happen.
 * - NEVER moves, renames, or deletes files; existing URLs, columns and storage
 *   paths are preserved.
 */
final class MigrateExistingMediaCommand extends Command
{
    protected $signature = 'media:migrate-existing
        {--execute : Actually create the missing Media records}
        {--dry-run : Report only (this is the default when --execute is omitted)}';

    protected $description = 'Register every existing image file as a Media record (idempotent, safe).';

    private array $report = [
        'scanned' => 0,
        'already' => 0,
        'wouldCreate' => 0,
        'created' => 0,
        'duplicates' => [],
        'missing' => [],
        'skipped' => [],
    ];

    private array $seen = [];

    public function handle(): int
    {
        $dryRun = ! $this->option('execute');

        $profile = app(MunicipalityRepositoryInterface::class)->getProfile();

        if (! $profile) {
            $this->warn('No Municipality profile found — Media records are scoped to the municipality, so creation is skipped. Seed a municipality profile first.');

            return self::FAILURE;
        }

        $this->info($dryRun ? 'DRY RUN — no records will be created.' : 'EXECUTE — creating missing Media records...');

        $sources = $this->sources();

        foreach ($sources as $source) {
            $model = $source['model'];
            $this->line("Scanning {$model} ...");

            $query = $model::query();

            foreach ($query->cursor() as $record) {
                $collection = $this->resolveCollection($source, $record);

                foreach ($source['columns'] as $column) {
                    $path = $record->{$column};
                    if (is_string($path) && $path !== '') {
                        $this->register($path, $collection, $this->attr($record, $source, 'title'), $this->attr($record, $source, 'alt'), $dryRun, $profile);
                    }
                }

                if (! empty($source['gallery'])) {
                    $gallery = $record->{$source['gallery']};
                    if (is_array($gallery)) {
                        foreach ($gallery as $path) {
                            if (is_string($path) && $path !== '') {
                                $this->register($path, $source['galleryCollection'], $this->attr($record, $source, 'title'), $this->attr($record, $source, 'alt'), $dryRun, $profile);
                            }
                        }
                    }
                }
            }
        }

        $this->printReport();

        if ($dryRun) {
            $this->info('Dry run complete. Re-run with --execute to create the missing records.');
        } else {
            $this->info('Migration complete.');
        }

        return self::SUCCESS;
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function sources(): array
    {
        return [
            [
                'model' => NewsItem::class,
                'columns' => ['cover_image_path', 'mobile_image_path'],
                'collection' => MediaCollection::News->value,
                'title' => 'title_ar',
                'alt' => 'title_ar',
            ],
            [
                'model' => Announcement::class,
                'columns' => ['desktop_image_path', 'mobile_image_path'],
                'collection' => MediaCollection::Announcements->value,
                'title' => 'title',
                'alt' => 'title',
            ],
            [
                'model' => Project::class,
                'columns' => ['cover_image_path'],
                'gallery' => 'gallery',
                'collection' => MediaCollection::Projects->value,
                'galleryCollection' => MediaCollection::ProjectGallery->value,
                'title' => 'name_ar',
                'alt' => 'name_ar',
            ],
            [
                'model' => CouncilMember::class,
                'columns' => ['photo_path'],
                'collection' => MediaCollection::CouncilMembers->value,
                'title' => 'full_name',
                'alt' => 'full_name',
            ],
            [
                'model' => Department::class,
                'columns' => ['cover_image_path'],
                'collection' => MediaCollection::Departments->value,
                'title' => 'name',
                'alt' => 'name',
            ],
            [
                'model' => Facility::class,
                'columns' => ['cover_image_path'],
                'gallery' => 'gallery',
                'collection' => MediaCollection::Facilities->value,
                'galleryCollection' => MediaCollection::FacilityGallery->value,
                'title' => 'name',
                'alt' => 'name',
            ],
            [
                'model' => HomepageSlide::class,
                'columns' => ['image_path', 'mobile_image_path'],
                'collection' => 'dynamic',
                'title' => 'title',
                'alt' => 'title',
            ],
            [
                'model' => HomepageSetting::class,
                'columns' => ['mayor_image_path'],
                'collection' => MediaCollection::Mayor->value,
                'title' => 'mayor',
                'alt' => 'mayor',
            ],
            [
                'model' => ServiceCategory::class,
                'columns' => ['image_path'],
                'collection' => MediaCollection::Services->value,
                'title' => 'name',
                'alt' => 'name',
            ],
        ];
    }

    private function resolveCollection(array $source, Model $record): string
    {
        if ($source['collection'] !== 'dynamic') {
            return $source['collection'];
        }

        $pageKey = method_exists($record, 'page_key') ? $record->page_key : null;

        return $pageKey === 'home' ? MediaCollection::Hero->value : MediaCollection::PageCarousel->value;
    }

    private function attr(Model $record, array $source, string $key): ?string
    {
        $field = $source[$key] ?? null;

        if (! $field) {
            return null;
        }

        $value = $record->{$field};

        return is_string($value) && $value !== '' ? $value : null;
    }

    private function register(string $path, string $collection, ?string $title, ?string $alt, bool $dryRun, Municipality $profile): void
    {
        $this->report['scanned']++;

        if (Media::where('path', $path)->exists() || isset($this->seen[$path])) {
            if (! isset($this->seen[$path])) {
                $this->seen[$path] = true;
                $this->report['already']++;
            } else {
                $this->report['duplicates'][] = $path;
            }

            return;
        }

        if (! Storage::disk('public')->exists($path)) {
            $this->report['missing'][] = $path;

            return;
        }

        $full = Storage::disk('public')->path($path);
        $mime = mime_content_type($full) ?: 'image';
        $size = filesize($full);
        $width = null;
        $height = null;

        if (str_starts_with((string) $mime, 'image/') && ($info = getimagesize($full)) !== false) {
            $width = $info[0];
            $height = $info[1];
        }

        if ($dryRun) {
            $this->report['wouldCreate']++;
            $this->line("  would create [{$collection}] {$path}");
        } else {
            Media::create([
                'mediable_type' => $profile->getMorphClass(),
                'mediable_id' => $profile->getKey(),
                'collection' => $collection,
                'disk' => 'public',
                'path' => $path,
                'mime_type' => $mime,
                'size' => $size,
                'width' => $width,
                'height' => $height,
                'title' => $title,
                'alt' => $alt,
                'display_order' => 0,
                'is_active' => true,
            ]);
            $this->report['created']++;
            $this->line("  created [{$collection}] {$path}");
        }

        $this->seen[$path] = true;
    }

    private function printReport(): void
    {
        $this->newLine();
        $this->info('==================== MIGRATION REPORT ====================');
        $this->line('Images scanned       : '.$this->report['scanned']);
        $this->line('Already Media records : '.$this->report['already']);
        $this->line('Would create         : '.$this->report['wouldCreate']);
        $this->line('Created              : '.$this->report['created']);
        $this->line('Duplicate (reused)   : '.count(array_unique($this->report['duplicates'])));
        $this->line('Missing files         : '.count($this->report['missing']));

        if ($this->report['missing'] !== []) {
            $this->newLine();
            $this->warn('Missing files (referenced but not on disk — skipped):');
            foreach (array_unique($this->report['missing']) as $path) {
                $this->line('  - '.$path);
            }
        }
    }
}
