<?php

declare(strict_types=1);

namespace App\Domains\Homepage\Repositories;

use App\Domains\Announcements\Enums\AnnouncementType;
use App\Domains\Announcements\Models\Announcement;
use App\Domains\Department\Models\Department;
use App\Domains\ElectronicServices\Models\ElectronicService;
use App\Domains\EngineeringOffices\Models\EngineeringOffice;
use App\Domains\Homepage\Contracts\HomepagePublicRepositoryInterface;
use App\Domains\Homepage\Models\HomepageQuickLink;
use App\Domains\Homepage\Models\HomepageSection;
use App\Domains\Homepage\Models\HomepageSetting;
use App\Domains\Homepage\Models\HomepageSlide;
use App\Domains\Homepage\Models\HomepageStatistic;
use App\Domains\Jobs\Models\Job;
use App\Domains\Municipality\Enums\CouncilMemberPosition;
use App\Domains\Municipality\Models\CouncilDecision;
use App\Domains\Municipality\Models\CouncilMember;
use App\Domains\Municipality\Models\Municipality;
use App\Domains\News\Enums\NewsCategory;
use App\Domains\News\Models\NewsItem;
use App\Domains\Projects\Enums\ProjectStatus;
use App\Domains\Projects\Models\Project;
use App\Domains\PublicFacilities\Models\Facility;
use App\Domains\SharedKernel\Models\Media;
use App\Domains\Tenders\Models\Tender;
use App\Domains\WaterSchedule\Models\WaterArea;
use App\Domains\WaterSchedule\Models\WaterSchedule;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;

final class EloquentHomepagePublicRepository implements HomepagePublicRepositoryInterface
{
    private const CACHE_KEY = 'homepage.public.data';

    private const CACHE_TTL = 600;

    public function getHomePageData(): array
    {
        return Cache::remember(self::CACHE_KEY, self::CACHE_TTL, function (): array {
            $settings = $this->getSettings();
            $enabledSections = HomepageSection::where('is_enabled', true)
                ->orderBy('sort_order')
                ->get(['key', 'title', 'subtitle', 'items_limit', 'settings']);

            $sectionsData = $enabledSections->map(fn ($s) => [
                'key' => $s->key,
                'title' => $s->title,
                'subtitle' => $s->subtitle,
                'items_limit' => $s->items_limit,
                'settings' => $s->settings,
            ])->toArray();

            $data = [
                'settings' => $settings,
                'sections' => $sectionsData,
                'slides' => [],
                'quickLinks' => [],
                'statistics' => [],
                'autoStatistics' => [],
                'municipality' => $this->getMunicipalityInfo(),
                'mayor' => null,
                'featuredServices' => [],
                'featuredDepartments' => [],
                'featuredCouncilMembers' => [],
                'latestCouncilDecisions' => [],
                'engineeringOffices' => [],
                'latestNews' => [],
                'latestProjects' => [],
                'latestTenders' => [],
                'latestAnnouncements' => [],
                'latestJobs' => [],
                'waterSchedule' => [],
                'waterAreas' => [],
                'partnerLogos' => [],
                'enabledSections' => $enabledSections->pluck('key')->toArray(),
            ];

            foreach ($enabledSections as $section) {
                $data = match ($section->key) {
                    'hero' => array_merge($data, ['slides' => $this->getHeroSlides()]),
                    'quick_links' => array_merge($data, ['quickLinks' => $this->getQuickLinks()]),
                    'statistics' => array_merge($data, [
                        'statistics' => $this->getStatistics(),
                        'autoStatistics' => $this->getAutoStatistics(),
                        'statisticsBg' => $this->getStatisticsBackground(),
                    ]),
                    'municipality_intro' => array_merge($data, ['municipality' => $this->getMunicipalityInfo()]),
                    'services' => array_merge($data, ['featuredServices' => $this->getFeaturedServices($section->items_limit ?? 6)]),
                    'departments' => array_merge($data, ['featuredDepartments' => $this->getFeaturedDepartments($section->items_limit ?? 6)]),
                    'council_members' => array_merge($data, ['featuredCouncilMembers' => $this->getFeaturedCouncilMembers($section->items_limit ?? 6)]),
                    'facilities' => array_merge($data, ['facilities' => $this->getFeaturedFacilities($section->items_limit ?? 4)]),
                    'council_decisions' => array_merge($data, ['latestCouncilDecisions' => $this->getLatestCouncilDecisions($section->items_limit ?? 8)]),
                    'engineering_offices' => array_merge($data, ['engineeringOffices' => $this->getEngineeringOffices($section->items_limit ?? 6)]),
                    'latest_news' => array_merge($data, ['latestNews' => $this->getLatestNews($section->items_limit ?? 4)]),
                    'projects' => array_merge($data, ['latestProjects' => $this->getLatestProjects($section->items_limit ?? 3)]),
                    'tenders' => array_merge($data, ['latestTenders' => $this->getLatestTenders($section->items_limit ?? 4)]),
                    'announcements' => array_merge($data, ['latestAnnouncements' => $this->getLatestAnnouncements($section->items_limit ?? 3)]),
                    default => $data,
                };
            }

            // Always fetch supporting data regardless of section toggle
            $data['mayor'] = $this->getMayorData();
            $data['latestJobs'] = $this->getLatestJobs(3);
            $data['waterSchedule'] = $this->getWaterSchedule();
            $data['waterAreas'] = $this->getWaterAreas();
            $data['partnerLogos'] = $this->getPartnerLogos();

            return $data;
        });
    }

    public function getHeroSlides(): array
    {
        return HomepageSlide::where('page_key', 'home')
            ->where('is_active', true)
            ->where(function (Builder $query): void {
                $query->whereNull('starts_at')->orWhere('starts_at', '<=', now());
            })
            ->where(function (Builder $query): void {
                $query->whereNull('ends_at')->orWhere('ends_at', '>=', now());
            })
            ->orderBy('sort_order')
            ->get(['id', 'title', 'subtitle', 'description', 'badge_text', 'image_path', 'button_text', 'button_url', 'sort_order', 'is_active', 'starts_at', 'ends_at'])
            ->toArray();
    }

    public function getQuickLinks(): array
    {
        return HomepageQuickLink::where('is_active', true)
            ->orderBy('sort_order')
            ->get(['id', 'title', 'url', 'icon', 'is_external', 'sort_order'])
            ->toArray();
    }

    public function getStatistics(): array
    {
        return HomepageStatistic::where('is_active', true)
            ->orderBy('sort_order')
            ->get(['id', 'label', 'value', 'suffix', 'icon', 'description', 'sort_order'])
            ->toArray();
    }

    public function getAutoStatistics(): array
    {
        $stats = [];

        try {
            if (class_exists(ElectronicService::class)) {
                $count = ElectronicService::where('status', 'active')->where('is_public', true)->count();
                if ($count > 0) {
                    $stats[] = [
                        'label' => 'خدمة إلكترونية',
                        'value' => (string) $count,
                        'suffix' => '',
                        'icon' => 'laptop',
                        'description' => 'خدمة متاحة أونلاين',
                    ];
                }
            }
        } catch (\Exception) {
        }

        try {
            if (class_exists(Facility::class)) {
                $count = Facility::where('status', 'active')->count();
                if ($count > 0) {
                    $stats[] = [
                        'label' => 'مرفق عام',
                        'value' => (string) $count,
                        'suffix' => '',
                        'icon' => 'building-2',
                        'description' => 'منشأة ومرافق',
                    ];
                }
            }
        } catch (\Exception) {
        }

        try {
            if (class_exists(WaterArea::class)) {
                $count = WaterArea::where('is_active', true)->count();
                if ($count > 0) {
                    $stats[] = [
                        'label' => 'منطقة مياه',
                        'value' => (string) $count,
                        'suffix' => '',
                        'icon' => 'droplet',
                        'description' => 'منطقة مشمولة بالخدمة',
                    ];
                }
            }
        } catch (\Exception) {
        }

        try {
            if (class_exists(Job::class)) {
                $count = Job::published()->count();
                if ($count > 0) {
                    $stats[] = [
                        'label' => 'وظيفة شاغرة',
                        'value' => (string) $count,
                        'suffix' => '',
                        'icon' => 'briefcase',
                        'description' => 'فرص عمل متاحة',
                    ];
                }
            }
        } catch (\Exception) {
        }

        return $stats;
    }

    public function getMunicipalityInfo(): ?array
    {
        if (! class_exists(Municipality::class)) {
            return null;
        }

        $municipality = Municipality::first();

        if (! $municipality) {
            return null;
        }

        $logo = $municipality->media()
            ->where('collection', 'logo')
            ->where('is_active', true)
            ->first(['path', 'alt', 'title']);

        $images = $municipality->media()
            ->where('collection', 'images')
            ->where('is_active', true)
            ->orderBy('display_order')
            ->get(['path', 'alt', 'title', 'display_order']);

        $mayorImg = $municipality->media()
            ->where('collection', 'mayor')
            ->where('is_active', true)
            ->latest()
            ->first(['path', 'alt']);

        return [
            'name_ar' => $municipality->name_ar,
            'name_en' => $municipality->name_en,
            'short_description' => $municipality->short_description,
            'full_description' => $municipality->full_description,
            'vision' => $municipality->vision,
            'mission' => $municipality->mission,
            'values' => $municipality->objectives,
            'foundation_date' => $municipality->foundation_date?->toDateString(),
            'population' => $municipality->population,
            'area' => $municipality->area,
            'logo_url' => $logo ? asset('storage/'.$logo->path) : null,
            'mayor_image_url' => $mayorImg ? asset('storage/'.$mayorImg->path) : null,
            'images' => $images->map(fn ($img) => [
                'url' => asset('storage/'.$img->path),
                'alt' => $img->alt,
            ])->toArray(),
            'contacts' => $municipality->contacts()
                ->where('is_active', true)
                ->orderBy('display_order')
                ->get(['id', 'type', 'value', 'label', 'url', 'display_order'])
                ->toArray(),
            'social_platforms' => $municipality->socialPlatforms()
                ->where('is_active', true)
                ->orderBy('display_order')
                ->get(['id', 'name', 'icon', 'url', 'display_order'])
                ->toArray(),
            'external_platforms' => $municipality->externalPlatforms()
                ->where('is_active', true)
                ->orderBy('display_order')
                ->get(['id', 'name', 'url', 'icon', 'display_order'])
                ->toArray(),
            'business_hours' => $municipality->businessHours()
                ->get(['id', 'day', 'opening_time', 'closing_time', 'is_closed'])
                ->toArray(),
            'emergency_contacts' => $municipality->emergencyContacts()
                ->where('is_active', true)
                ->get(['id', 'name', 'department', 'phone', 'icon'])
                ->toArray(),
        ];
    }

    public function getFeaturedServices(int $limit = 6): array
    {
        if (! class_exists(ElectronicService::class)) {
            return [];
        }

        return ElectronicService::query()
            ->where('status', 'active')
            ->where('is_public', true)
            ->where('is_featured', true)
            ->orderBy('sort_order')
            ->with('category:id,name,slug')
            ->limit($limit)
            ->get(['id', 'name', 'slug', 'summary', 'portal_url', 'requires_login', 'processing_time', 'service_category_id', 'department_id', 'sort_order'])
            ->toArray();
    }

    public function getFeaturedDepartments(int $limit = 6): array
    {
        if (! class_exists(Department::class)) {
            return [];
        }

        $departments = Department::query()
            ->where('status', 'active')
            ->where('is_public', true)
            ->orderBy('is_featured', 'desc')
            ->orderBy('display_order')
            ->limit($limit)
            ->get(['id', 'name', 'slug', 'short_description', 'icon', 'cover_image_path', 'manager_name', 'manager_position', 'phone', 'email', 'office_location', 'working_hours', 'vision', 'mission', 'responsibilities', 'is_featured', 'display_order']);

        $departmentIds = $departments->pluck('id');

        $serviceCounts = ElectronicService::whereIn('department_id', $departmentIds)
            ->where('is_public', true)
            ->where('status', 'active')
            ->selectRaw('department_id, COUNT(*) as count')
            ->groupBy('department_id')
            ->pluck('count', 'department_id');

        return $departments->map(function ($dept) use ($serviceCounts) {
            return [
                'id' => $dept->id,
                'name' => $dept->name,
                'slug' => $dept->slug,
                'short_description' => $dept->short_description,
                'description' => $dept->description,
                'icon' => $dept->icon,
                'cover_image_url' => $dept->cover_image_url,
                'manager_name' => $dept->manager_name,
                'manager_position' => $dept->manager_position,
                'phone' => $dept->phone,
                'email' => $dept->email,
                'office_location' => $dept->office_location,
                'working_hours' => $dept->working_hours,
                'vision' => $dept->vision,
                'mission' => $dept->mission,
                'responsibilities' => $dept->responsibilities,
                'is_featured' => $dept->is_featured,
                'services_count' => (int) ($serviceCounts[$dept->id] ?? 0),
            ];
        })->toArray();
    }

    public function getFeaturedCouncilMembers(int $limit = 6): array
    {
        if (! class_exists(CouncilMember::class)) {
            return [];
        }

        $members = CouncilMember::query()
            ->where('status', 'active')
            ->where('is_public', true)
            ->orderBy('is_featured', 'desc')
            ->orderBy('display_order')
            ->limit($limit)
            ->get(['id', 'full_name', 'slug', 'position', 'bio', 'qualification', 'committee', 'photo_path', 'years_of_experience', 'is_featured', 'display_order'])
            ->toArray();

        foreach ($members as &$member) {
            if (! empty($member['photo_path'])) {
                $member['photo_url'] = asset('storage/'.$member['photo_path']);
            }
            try {
                $member['position_label'] = CouncilMemberPosition::tryFrom($member['position'] ?? '')?->label() ?? ($member['position'] ?? '');
            } catch (\Throwable) {
                $member['position_label'] = $member['position'] ?? '';
            }
        }

        return $members;
    }

    public function getMayorData(): ?array
    {
        if (! class_exists(CouncilMember::class)) {
            return $this->getMayorFromSettings();
        }

        try {
            $mayor = CouncilMember::query()
                ->where('position', 'mayor')
                ->where('status', 'active')
                ->where('is_public', true)
                ->first(['id', 'full_name', 'slug', 'position', 'bio', 'qualification', 'committee', 'photo_path', 'years_of_experience']);

            if ($mayor) {
                $data = $mayor->toArray();
                if (! empty($data['photo_path'])) {
                    $data['photo_url'] = asset('storage/'.$data['photo_path']);
                }

                return $data;
            }
        } catch (\Exception) {
        }

        return $this->getMayorFromSettings();
    }

    private function getMayorFromSettings(): ?array
    {
        $settings = HomepageSetting::first(['site_title', 'mayor_message', 'mayor_image_path', 'show_mayor_message']);
        if (! $settings) {
            return null;
        }

        if (! $settings->show_mayor_message && ! $settings->mayor_message) {
            return null;
        }

        return [
            'full_name' => $settings->site_title ?? 'رئيس البلدية',
            'position' => 'mayor',
            'position_label' => 'رئيس المجلس البلدي',
            'bio' => $settings->mayor_message,
            'photo_url' => $settings->mayor_image_path
                ? asset('storage/'.$settings->mayor_image_path)
                : null,
        ];
    }

    public function getLatestCouncilDecisions(int $limit = 5): array
    {
        if (! class_exists(CouncilDecision::class)) {
            return [];
        }

        return CouncilDecision::query()
            ->where('status', 'published')
            ->where('is_public', true)
            ->whereNotNull('published_at')
            ->orderBy('decision_date', 'desc')
            ->limit($limit)
            ->get(['id', 'title', 'decision_number', 'session_number', 'type', 'summary', 'decision_date', 'attachment_path', 'published_at'])
            ->toArray();
    }

    public function getEngineeringOffices(int $limit = 6): array
    {
        if (! class_exists(EngineeringOffice::class)) {
            return [];
        }

        return EngineeringOffice::query()
            ->where(function (Builder $q): void {
                $q->whereNull('expires_at')
                    ->orWhere('expires_at', '>=', now()->toDateString());
            })
            ->where('approval_status', 'approved')
            ->where('status', 'active')
            ->where('is_public', true)
            ->orderBy('sort_order')
            ->limit($limit)
            ->get(['id', 'office_name', 'engineer_name', 'license_number', 'specializations', 'phone', 'mobile', 'approval_status', 'status', 'sort_order'])
            ->toArray();
    }

    public function getLatestNews(int $limit = 3): array
    {
        if (! class_exists(NewsItem::class)) {
            return [];
        }

        try {
            $news = NewsItem::query()
                ->where('status', 'published')
                ->where('is_public', true)
                ->where('publish_at', '<=', now())
                ->orderBy('is_featured', 'desc')
                ->orderBy('publish_at', 'desc')
                ->limit($limit)
                ->get(['id', 'title_ar', 'slug', 'category', 'summary', 'cover_image_path', 'publish_at', 'is_featured']);

            return $news->map(function ($n) {
                $routeExists = Route::has('public.news.show');

                return [
                    'id' => $n->id,
                    'title' => $n->title_ar,
                    'slug' => $n->slug,
                    'url' => $routeExists ? route('public.news.show', $n->slug) : '#',
                    'image' => $n->cover_image_path ? asset('storage/'.$n->cover_image_path) : null,
                    'category' => $n->category instanceof NewsCategory ? $n->category->label() : $n->category,
                    'summary' => $n->summary ?? '',
                    'date' => $n->publish_at?->format('Y-m-d') ?? '',
                ];
            })->toArray();
        } catch (\Throwable $e) {
            return [];
        }
    }

    public function getLatestProjects(int $limit = 3): array
    {
        if (! class_exists(Project::class)) {
            return [];
        }

        try {
            $projects = Project::query()
                ->where('status', 'published')
                ->where('is_public', true)
                ->orderBy('is_featured', 'desc')
                ->orderBy('created_at', 'desc')
                ->limit($limit)
                ->get(['id', 'name_ar', 'slug', 'project_status', 'summary', 'cover_image_path', 'implementation_percentage', 'is_featured']);

            return $projects->map(function ($p) {
                $routeExists = Route::has('public.projects.show');

                return [
                    'id' => $p->id,
                    'title' => $p->name_ar,
                    'slug' => $p->slug,
                    'url' => $routeExists ? route('public.projects.show', $p->slug) : '#',
                    'image' => $p->cover_image_path ? asset('storage/'.$p->cover_image_path) : null,
                    'status' => $p->project_status instanceof ProjectStatus ? $p->project_status->label() : $p->project_status,
                    'summary' => $p->summary ?? '',
                    'progress' => $p->implementation_percentage,
                ];
            })->toArray();
        } catch (\Throwable $e) {
            return [];
        }
    }

    public function getLatestAnnouncements(int $limit = 3): array
    {
        if (! class_exists(Announcement::class)) {
            return [];
        }

        try {
            $announcements = Announcement::query()
                ->where('status', 'published')
                ->where('published_at', '<=', now())
                ->orderBy('is_featured', 'desc')
                ->orderBy('published_at', 'desc')
                ->limit($limit)
                ->get(['id', 'title', 'slug', 'type', 'priority', 'short_description', 'desktop_image_path', 'published_at', 'is_featured']);

            return $announcements->map(function ($a) {
                $routeExists = Route::has('public.announcements.show');
                $slug = $a->slug;

                return [
                    'id' => $a->id,
                    'title' => $a->title,
                    'slug' => $slug,
                    'url' => $routeExists ? route('public.announcements.show', $slug) : '#',
                    'image' => $a->desktop_image_path ? asset('storage/'.$a->desktop_image_path) : null,
                    'type' => $a->type instanceof AnnouncementType ? $a->type->label() : $a->type,
                    'summary' => $a->short_description ?? '',
                    'date' => $a->published_at?->format('Y-m-d') ?? '',
                ];
            })->toArray();
        } catch (\Throwable $e) {
            return [];
        }
    }

    public function getLatestTenders(int $limit = 4): array
    {
        if (! class_exists(Tender::class)) {
            return [];
        }

        try {
            $tenders = Tender::query()
                ->where('status', 'published')
                ->where('is_public', true)
                ->where('submission_deadline', '>=', now()->toDateString())
                ->orderBy('is_featured', 'desc')
                ->orderBy('submission_deadline', 'asc')
                ->limit($limit)
                ->get(['id', 'title_ar', 'slug', 'tender_number', 'summary', 'submission_deadline', 'budget', 'status', 'is_featured']);

            return $tenders->map(function ($t) {
                $routeExists = Route::has('public.tenders.show');

                return [
                    'id' => $t->id,
                    'title' => $t->title_ar ?? $t->tender_number,
                    'slug' => $t->slug,
                    'url' => $routeExists ? route('public.tenders.show', $t->slug) : '#',
                    'tender_number' => $t->tender_number,
                    'summary' => $t->summary ?? '',
                    'deadline' => $t->submission_deadline?->format('Y-m-d') ?? '',
                    'budget' => $t->budget,
                    'is_featured' => $t->is_featured,
                ];
            })->toArray();
        } catch (\Throwable $e) {
            return [];
        }
    }

    public function getLatestJobs(int $limit = 3): array
    {
        if (! class_exists(Job::class)) {
            return [];
        }

        try {
            return Job::query()
                ->where('status', 'published')
                ->where('is_public', true)
                ->where('publish_at', '<=', now()->toDateString())
                ->where('closing_at', '>=', now()->toDateString())
                ->orderBy('is_featured', 'desc')
                ->orderBy('publish_at', 'desc')
                ->limit($limit)
                ->get(['id', 'title', 'slug', 'employment_type', 'location', 'summary', 'vacancies', 'closing_at', 'publish_at', 'is_featured', 'status'])
                ->toArray();
        } catch (\Exception $e) {
            return [];
        }
    }

    public function getWaterSchedule(): array
    {
        if (! class_exists(WaterSchedule::class)) {
            return [];
        }

        try {
            $today = now()->toDateString();

            $schedules = WaterSchedule::query()
                ->with(['area:id,name'])
                ->where('schedule_date', $today)
                ->where('is_public', true)
                ->orderBy('display_order')
                ->get(['id', 'area_id', 'schedule_date', 'start_time', 'end_time', 'status', 'notes', 'display_order', 'is_public']);

            if ($schedules->isEmpty()) {
                $latestDate = WaterSchedule::query()
                    ->where('is_public', true)
                    ->where('schedule_date', '<', $today)
                    ->max('schedule_date');

                if ($latestDate) {
                    $schedules = WaterSchedule::query()
                        ->with(['area:id,name'])
                        ->where('schedule_date', $latestDate)
                        ->where('is_public', true)
                        ->orderBy('display_order')
                        ->get(['id', 'area_id', 'schedule_date', 'start_time', 'end_time', 'status', 'notes', 'display_order', 'is_public']);
                }
            }

            return $schedules->toArray();
        } catch (\Exception $e) {
            return [];
        }
    }

    public function getWaterAreas(): array
    {
        if (! class_exists(WaterArea::class)) {
            return [];
        }

        try {
            return WaterArea::query()
                ->where('is_active', true)
                ->orderBy('display_order')
                ->get(['id', 'name', 'display_order'])
                ->toArray();
        } catch (\Exception $e) {
            return [];
        }
    }

    public function getStatisticsBackground(): ?string
    {
        if (! class_exists(Municipality::class)) {
            return null;
        }

        $municipality = Municipality::first();

        if (! $municipality) {
            return null;
        }

        $bg = $municipality->media()
            ->where('collection', 'statistics_bg')
            ->where('is_active', true)
            ->latest()
            ->first(['path']);

        return $bg ? asset('storage/'.$bg->path) : null;
    }

    public function getDepartmentPublicServices(int $departmentId, int $limit = 4): array
    {
        if (! class_exists(ElectronicService::class)) {
            return [];
        }

        return ElectronicService::query()
            ->where('department_id', $departmentId)
            ->where('status', 'active')
            ->where('is_public', true)
            ->orderBy('sort_order')
            ->with('category:id,name,slug')
            ->limit($limit)
            ->get(['id', 'name', 'slug', 'summary', 'icon', 'portal_url', 'category_id', 'department_id', 'sort_order'])
            ->toArray();
    }

    public function getFeaturedFacilities(int $limit = 4): array
    {
        if (! class_exists(Facility::class)) {
            return [];
        }

        try {
            $facilities = Facility::query()
                ->with('category:id,name,slug,icon')
                ->published()
                ->orderBy('is_featured', 'desc')
                ->orderBy('display_order')
                ->orderBy('name')
                ->limit($limit)
                ->get();

            return $facilities->map(fn ($f) => [
                'id' => $f->id,
                'name' => $f->name,
                'slug' => $f->slug,
                'summary' => $f->summary,
                'description' => $f->description,
                'cover_image_path' => $f->cover_image_path,
                'cover_image_url' => $f->cover_image_url,
                'address' => $f->address,
                'phone' => $f->phone,
                'email' => $f->email,
                'working_hours' => $f->working_hours,
                'services' => $f->services,
                'features' => $f->features,
                'rules' => $f->rules,
                'status' => $f->status?->value,
                'is_public' => $f->is_public,
                'is_featured' => $f->is_featured,
                'display_order' => $f->display_order,
                'views_count' => $f->views_count,
                'category' => $f->category ? [
                    'id' => $f->category->id,
                    'name' => $f->category->name,
                    'slug' => $f->category->slug,
                    'icon' => $f->category->icon,
                ] : null,
            ])->toArray();
        } catch (\Exception) {
            return [];
        }
    }

    public function getPartnerLogos(): array
    {
        try {
            $logos = Media::query()
                ->where('collection', 'partner_logo')
                ->where('is_active', true)
                ->orderBy('display_order')
                ->get(['path', 'title', 'alt', 'display_order']);

            return $logos->map(fn ($media) => [
                'url' => asset('storage/'.$media->path),
                'title' => $media->title,
                'alt' => $media->alt,
            ])->toArray();
        } catch (\Exception $e) {
            return [];
        }
    }

    private function getSettings(): array
    {
        $settings = HomepageSetting::first();

        if (! $settings) {
            return [];
        }

        return $settings->toArray();
    }
}
