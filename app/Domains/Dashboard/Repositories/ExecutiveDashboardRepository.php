<?php

declare(strict_types=1);

namespace App\Domains\Dashboard\Repositories;

use App\Domains\Authentication\Models\User;
use App\Domains\Dashboard\Contracts\DashboardRepositoryInterface;
use App\Domains\Department\Models\Department;
use App\Domains\ElectronicServices\Models\ElectronicService;
use App\Domains\ElectronicServices\Models\ServiceCategory;
use App\Domains\EngineeringOffices\Models\EngineeringOffice;
use App\Domains\Homepage\Models\HomepageQuickLink;
use App\Domains\Homepage\Models\HomepageSection;
use App\Domains\Homepage\Models\HomepageSetting;
use App\Domains\Homepage\Models\HomepageSlide;
use App\Domains\Homepage\Models\HomepageStatistic;
use App\Domains\Jobs\Models\Job;
use App\Domains\Municipality\Models\CouncilDecision;
use App\Domains\Municipality\Models\CouncilMember;
use App\Domains\Municipality\Models\Municipality;
use App\Domains\PublicFacilities\Models\Facility;
use App\Domains\WaterSchedule\Models\WaterArea;
use App\Domains\WaterSchedule\Models\WaterMaintenance;
use App\Domains\WaterSchedule\Models\WaterSchedule;
use Illuminate\Support\Facades\Cache;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

final readonly class ExecutiveDashboardRepository implements DashboardRepositoryInterface
{
    public function getExecutiveDashboard(): array
    {
        return Cache::remember('executive_dashboard_v2', 300, function () {
            $data = [];

            $data['header'] = $this->getHeader();
            $data['quickStats'] = $this->getQuickStats();
            $data['todayActivity'] = $this->getTodayActivity();
            $data['timeline'] = $this->getTimeline();
            $data['analytics'] = $this->getAnalytics();
            $data['overview'] = $this->getOverview();
            $data['waterStats'] = $this->getWaterStats();
            $data['jobStats'] = $this->getJobStats();
            $data['engineeringStats'] = $this->getEngineeringStats();
            $data['serviceStats'] = $this->getServiceStats();
            $data['homepageStats'] = $this->getHomepageStats();
            $data['systemHealth'] = $this->getSystemHealth();
            $data['quickActions'] = $this->getQuickActions();
            $data['upcomingEvents'] = $this->getUpcomingEvents();
            $data['notifications'] = $this->getNotifications();

            $data['modulesAvailable'] = $this->getModulesAvailable();

            return $data;
        });
    }

    private function getModulesAvailable(): array
    {
        return [
            'electronic_services' => class_exists(ElectronicService::class),
            'departments' => class_exists(Department::class),
            'council_decisions' => class_exists(CouncilDecision::class),
            'council_members' => class_exists(CouncilMember::class),
            'engineering_offices' => class_exists(EngineeringOffice::class),
            'jobs' => class_exists(Job::class),
            'public_facilities' => class_exists(Facility::class),
            'water_schedule' => class_exists(WaterSchedule::class),
            'homepage' => class_exists(HomepageSetting::class),
            'municipality' => class_exists(Municipality::class),
        ];
    }

    private function getHeader(): array
    {
        $municipalityName = 'بلدية إذنا';

        if (class_exists(Municipality::class)) {
            $municipality = Municipality::first();
            if ($municipality) {
                $municipalityName = $municipality->name_ar;
            }
        }

        return [
            'municipalityName' => $municipalityName,
            'date' => now()->translatedFormat('l d F Y'),
            'time' => now()->format('H:i'),
            'welcomeMessage' => 'مرحباً بعودتك',
        ];
    }

    private function getQuickStats(): array
    {
        $stats = [];

        if (class_exists(ElectronicService::class)) {
            $stats[] = [
                'key' => 'services',
                'label' => 'خدمة إلكترونية',
                'count' => ElectronicService::count(),
                'icon' => 'laptop',
                'color' => 'primary',
            ];
        }

        if (class_exists(Department::class)) {
            $stats[] = [
                'key' => 'departments',
                'label' => 'دائرة',
                'count' => Department::count(),
                'icon' => 'building-2',
                'color' => 'blue',
            ];
        }

        if (class_exists(CouncilMember::class)) {
            $stats[] = [
                'key' => 'council_members',
                'label' => 'عضو مجلس',
                'count' => CouncilMember::count(),
                'icon' => 'users',
                'color' => 'amber',
            ];
        }

        if (class_exists(CouncilDecision::class)) {
            $stats[] = [
                'key' => 'council_decisions',
                'label' => 'قرار مجلس',
                'count' => CouncilDecision::count(),
                'icon' => 'file-text',
                'color' => 'purple',
            ];
        }

        if (class_exists(EngineeringOffice::class)) {
            $stats[] = [
                'key' => 'engineering_offices',
                'label' => 'مكتب هندسي',
                'count' => EngineeringOffice::count(),
                'icon' => 'hard-hat',
                'color' => 'cyan',
            ];
        }

        if (class_exists(Job::class)) {
            $stats[] = [
                'key' => 'jobs',
                'label' => 'وظيفة',
                'count' => Job::count(),
                'icon' => 'briefcase',
                'color' => 'green',
            ];
        }

        if (class_exists(Facility::class)) {
            $stats[] = [
                'key' => 'facilities',
                'label' => 'مرفق عام',
                'count' => Facility::count(),
                'icon' => 'building-2',
                'color' => 'rose',
            ];
        }

        if (class_exists(WaterSchedule::class)) {
            $scheduleCount = WaterSchedule::where('schedule_date', now()->toDateString())->count();
            $stats[] = [
                'key' => 'water_schedules',
                'label' => 'جدول مياه اليوم',
                'count' => $scheduleCount,
                'icon' => 'droplets',
                'color' => 'sky',
            ];
        }

        return $stats;
    }

    private function getTodayActivity(): array
    {
        $today = now()->toDateString();
        $activities = [];

        if (class_exists(CouncilDecision::class)) {
            $todayDecision = CouncilDecision::whereDate('created_at', $today)
                ->latest()->first();
            if ($todayDecision) {
                $activities[] = [
                    'type' => 'قرار مجلس',
                    'title' => $todayDecision->title,
                    'time' => $todayDecision->created_at->diffForHumans(),
                    'icon' => 'file-text',
                ];
            }
        }

        if (class_exists(ElectronicService::class)) {
            $todayService = ElectronicService::whereDate('created_at', $today)
                ->latest()->first();
            if ($todayService) {
                $activities[] = [
                    'type' => 'خدمة إلكترونية',
                    'title' => $todayService->name,
                    'time' => $todayService->created_at->diffForHumans(),
                    'icon' => 'laptop',
                ];
            }
        }

        if (class_exists(Job::class)) {
            $todayJob = Job::whereDate('created_at', $today)
                ->latest()->first();
            if ($todayJob) {
                $activities[] = [
                    'type' => 'وظيفة',
                    'title' => $todayJob->title,
                    'time' => $todayJob->created_at->diffForHumans(),
                    'icon' => 'briefcase',
                ];
            }
        }

        if (class_exists(Facility::class)) {
            $todayFacility = Facility::whereDate('created_at', $today)
                ->latest()->first();
            if ($todayFacility) {
                $activities[] = [
                    'type' => 'مرفق عام',
                    'title' => $todayFacility->name,
                    'time' => $todayFacility->created_at->diffForHumans(),
                    'icon' => 'building-2',
                ];
            }
        }

        if (class_exists(EngineeringOffice::class)) {
            $todayOffice = EngineeringOffice::whereDate('created_at', $today)
                ->latest()->first();
            if ($todayOffice) {
                $activities[] = [
                    'type' => 'مكتب هندسي',
                    'title' => $todayOffice->office_name,
                    'time' => $todayOffice->created_at->diffForHumans(),
                    'icon' => 'hard-hat',
                ];
            }
        }

        return $activities;
    }

    private function getTimeline(): array
    {
        $items = [];

        $addTimelineItems = function ($models, string $type, string $titleField, string $icon, string $iconBg, string $iconColor) use (&$items) {
            foreach ($models as $item) {
                $items[] = [
                    'type' => $type,
                    'title' => $item->$titleField,
                    'user' => $item->creator?->name ?? 'النظام',
                    'time' => $item->created_at->diffForHumans(),
                    'icon' => $icon,
                    'iconBg' => $iconBg,
                    'iconColor' => $iconColor,
                    '_sort' => $item->created_at->timestamp,
                ];
            }
        };

        if (class_exists(CouncilDecision::class)) {
            $addTimelineItems(
                CouncilDecision::latest()->take(5)->get(),
                'قرار مجلس', 'title', 'file-text', 'bg-purple-100', 'text-purple-600'
            );
        }

        if (class_exists(ElectronicService::class)) {
            $addTimelineItems(
                ElectronicService::latest()->take(5)->get(),
                'خدمة إلكترونية', 'name', 'laptop', 'bg-blue-100', 'text-blue-600'
            );
        }

        if (class_exists(Job::class)) {
            $addTimelineItems(
                Job::latest()->take(5)->get(),
                'وظيفة', 'title', 'briefcase', 'bg-green-100', 'text-green-600'
            );
        }

        if (class_exists(Facility::class)) {
            $addTimelineItems(
                Facility::latest()->take(5)->get(),
                'مرفق عام', 'name', 'building-2', 'bg-rose-100', 'text-rose-600'
            );
        }

        if (class_exists(EngineeringOffice::class)) {
            $addTimelineItems(
                EngineeringOffice::latest()->take(5)->get(),
                'مكتب هندسي', 'office_name', 'hard-hat', 'bg-cyan-100', 'text-cyan-600'
            );
        }

        usort($items, fn ($a, $b) => $b['_sort'] - $a['_sort']);
        $items = array_slice($items, 0, 15);

        return array_map(fn ($item) => array_diff_key($item, ['_sort' => null]), $items);
    }

    private function getAnalytics(): array
    {
        $analytics = [];

        if (class_exists(ElectronicService::class)) {
            $totalViews = ElectronicService::sum('views_count');
            $totalClicks = ElectronicService::sum('portal_clicks_count');
            $mostViewed = ElectronicService::where('is_public', true)
                ->orderBy('views_count', 'desc')->take(5)->get(['name', 'views_count'])->toArray();
            $mostClicked = ElectronicService::where('is_public', true)
                ->orderBy('portal_clicks_count', 'desc')->take(5)->get(['name', 'portal_clicks_count'])->toArray();

            $analytics['serviceViews'] = $totalViews;
            $analytics['serviceClicks'] = $totalClicks;
            $analytics['mostViewedServices'] = $mostViewed;
            $analytics['mostClickedServices'] = $mostClicked;
        }

        if (class_exists(Facility::class)) {
            $mostViewedFacilities = Facility::where('is_public', true)
                ->orderBy('views_count', 'desc')->take(5)->get(['name', 'views_count'])->toArray();
            $analytics['mostViewedFacilities'] = $mostViewedFacilities;
        }

        if (class_exists(Job::class)) {
            $mostViewedJobs = Job::orderBy('views_count', 'desc')->take(5)->get(['title', 'views_count'])->toArray();
            $analytics['mostViewedJobs'] = $mostViewedJobs;
        }

        if (class_exists(ServiceCategory::class)) {
            $servicesByCategory = ServiceCategory::withCount('services')
                ->where('is_public', true)->orderBy('services_count', 'desc')->take(10)->get(['name', 'services_count'])->toArray();
            $analytics['servicesByCategory'] = $servicesByCategory;
        }

        return $analytics;
    }

    private function getOverview(): array
    {
        $overview = [];

        if (class_exists(ElectronicService::class)) {
            $overview['published_services'] = ElectronicService::where('is_public', true)->where('status', 'active')->count();
            $overview['hidden_services'] = ElectronicService::where(function ($q) {
                $q->where('is_public', false)->orWhere('status', '!=', 'active');
            })->count();
        }

        if (class_exists(Job::class)) {
            $overview['open_jobs'] = Job::where('status', 'published')
                ->where('closing_at', '>=', now()->toDateString())->count();
            $overview['closed_jobs'] = Job::where('status', 'closed')->count();
        }

        if (class_exists(EngineeringOffice::class)) {
            $overview['approved_offices'] = EngineeringOffice::where('approval_status', 'approved')->count();
            $overview['suspended_offices'] = EngineeringOffice::where('approval_status', 'suspended')->count();
            $overview['pending_offices'] = EngineeringOffice::where('approval_status', 'pending')->count();
            $overview['expired_offices'] = EngineeringOffice::where('approval_status', 'expired')->count();
        }

        return $overview;
    }

    private function getWaterStats(): array
    {
        if (! class_exists(WaterSchedule::class)) {
            return [];
        }

        $today = now()->toDateString();

        $todaySchedules = WaterSchedule::where('schedule_date', $today);

        return [
            'total_areas' => WaterArea::count(),
            'today_schedules' => (clone $todaySchedules)->count(),
            'available' => (clone $todaySchedules)->where('status', 'available')->count(),
            'low_pressure' => (clone $todaySchedules)->where('status', 'low_pressure')->count(),
            'maintenance' => (clone $todaySchedules)->where('status', 'maintenance')->count(),
            'emergency' => (clone $todaySchedules)->where('status', 'emergency')->count(),
            'no_water' => (clone $todaySchedules)->where('status', 'no_water')->count(),
            'active_maintenance' => WaterMaintenance::where('status', 'active')->count(),
            'upcoming_maintenance' => WaterMaintenance::where('status', 'active')
                ->where('starts_at', '>', now())->count(),
        ];
    }

    private function getJobStats(): array
    {
        if (! class_exists(Job::class)) {
            return [];
        }

        $today = now()->toDateString();
        $weekFromNow = now()->addWeek()->toDateString();

        return [
            'total' => Job::count(),
            'open' => Job::where('status', 'published')
                ->where('closing_at', '>=', $today)->count(),
            'closing_this_week' => Job::where('status', 'published')
                ->where('closing_at', '>=', $today)
                ->where('closing_at', '<=', $weekFromNow)->count(),
            'closed' => Job::where('status', 'closed')->count(),
            'draft' => Job::where('status', 'draft')->count(),
            'archived' => Job::where('status', 'archived')->count(),
            'total_views' => Job::sum('views_count'),
        ];
    }

    private function getEngineeringStats(): array
    {
        if (! class_exists(EngineeringOffice::class)) {
            return [];
        }

        return [
            'total' => EngineeringOffice::count(),
            'pending' => EngineeringOffice::where('approval_status', 'pending')->count(),
            'approved' => EngineeringOffice::where('approval_status', 'approved')->count(),
            'suspended' => EngineeringOffice::where('approval_status', 'suspended')->count(),
            'expired' => EngineeringOffice::where('approval_status', 'expired')->count(),
        ];
    }

    private function getServiceStats(): array
    {
        if (! class_exists(ElectronicService::class)) {
            return [];
        }

        $model = ElectronicService::class;

        return [
            'total' => $model::count(),
            'active' => $model::where('status', 'active')->count(),
            'draft' => $model::where('status', 'draft')->count(),
            'archived' => $model::where('status', 'archived')->count(),
            'total_views' => $model::sum('views_count'),
            'total_clicks' => $model::sum('portal_clicks_count'),
            'categories_count' => ServiceCategory::count(),
        ];
    }

    private function getHomepageStats(): array
    {
        $stats = [];

        if (class_exists(HomepageSlide::class)) {
            $stats['slides_count'] = HomepageSlide::count();
            $stats['active_slides'] = HomepageSlide::where('is_active', true)->count();
            $stats['expired_slides'] = HomepageSlide::where('is_active', true)
                ->where('ends_at', '<', now())->count();
        }

        if (class_exists(HomepageQuickLink::class)) {
            $stats['quick_links_count'] = HomepageQuickLink::count();
            $stats['active_quick_links'] = HomepageQuickLink::where('is_active', true)->count();
        }

        if (class_exists(HomepageSection::class)) {
            $stats['sections_count'] = HomepageSection::count();
            $stats['enabled_sections'] = HomepageSection::where('is_enabled', true)->count();
        }

        if (class_exists(HomepageStatistic::class)) {
            $stats['statistics_count'] = HomepageStatistic::count();
            $stats['active_statistics'] = HomepageStatistic::where('is_active', true)->count();
        }

        return $stats;
    }

    private function getSystemHealth(): array
    {
        $userCount = User::count();
        $activeUsers = User::where('status', 'active')->count();

        $permissionCount = 0;
        $roleCount = 0;
        if (class_exists(Permission::class)) {
            $permissionCount = Permission::count();
        }
        if (class_exists(Role::class)) {
            $roleCount = Role::count();
        }

        $storagePath = storage_path('app/public');
        $storageSize = '--';
        if (is_dir($storagePath)) {
            $size = 0;
            foreach (new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($storagePath, \FilesystemIterator::SKIP_DOTS)) as $file) {
                $size += $file->getSize();
            }
            $storageSize = $this->formatBytes($size);
        }

        return [
            'users_count' => $userCount,
            'active_users' => $activeUsers,
            'permissions_count' => $permissionCount,
            'roles_count' => $roleCount,
            'laravel_version' => app()->version(),
            'php_version' => PHP_VERSION,
            'cache_driver' => config('cache.default'),
            'storage_usage' => $storageSize,
            'debug_mode' => config('app.debug'),
            'environment' => app()->environment(),
        ];
    }

    private function getQuickActions(): array
    {
        $actions = [];

        if (class_exists(ElectronicService::class)) {
            $actions[] = [
                'label' => 'إضافة خدمة إلكترونية',
                'route' => 'dashboard.electronic-services.services.create',
                'icon' => 'laptop',
                'color' => 'blue',
            ];
        }

        if (class_exists(Job::class)) {
            $actions[] = [
                'label' => 'إضافة وظيفة',
                'route' => 'dashboard.jobs.create',
                'icon' => 'briefcase',
                'color' => 'green',
            ];
        }

        if (class_exists(CouncilDecision::class)) {
            $actions[] = [
                'label' => 'إضافة قرار مجلس',
                'route' => 'dashboard.municipality.council-decisions.create',
                'icon' => 'file-text',
                'color' => 'purple',
            ];
        }

        if (class_exists(Facility::class)) {
            $actions[] = [
                'label' => 'إضافة مرفق عام',
                'route' => 'dashboard.facilities.create',
                'icon' => 'building-2',
                'color' => 'rose',
            ];
        }

        if (class_exists(EngineeringOffice::class)) {
            $actions[] = [
                'label' => 'إضافة مكتب هندسي',
                'route' => 'dashboard.engineering-offices.create',
                'icon' => 'hard-hat',
                'color' => 'cyan',
            ];
        }

        if (class_exists(Department::class)) {
            $actions[] = [
                'label' => 'إضافة دائرة',
                'route' => 'dashboard.departments.create',
                'icon' => 'building-2',
                'color' => 'amber',
            ];
        }

        return $actions;
    }

    private function getUpcomingEvents(): array
    {
        $events = collect();

        if (class_exists(Job::class)) {
            Job::where('status', 'published')
                ->where('closing_at', '>=', now()->toDateString())
                ->where('closing_at', '<=', now()->addWeek()->toDateString())
                ->orderBy('closing_at')
                ->take(5)
                ->get()
                ->each(function ($job) use ($events) {
                    $events->push([
                        'type' => 'job',
                        'title' => $job->title,
                        'date' => $job->closing_at->format('Y-m-d'),
                        'dateFormatted' => $job->closing_at->diffForHumans(),
                        'icon' => 'briefcase',
                        'color' => 'orange',
                    ]);
                });
        }

        if (class_exists(WaterMaintenance::class)) {
            WaterMaintenance::where('status', 'active')
                ->where('starts_at', '>=', now())
                ->orderBy('starts_at')
                ->take(5)
                ->get()
                ->each(function ($m) use ($events) {
                    $events->push([
                        'type' => 'maintenance',
                        'title' => $m->title,
                        'date' => $m->starts_at->format('Y-m-d'),
                        'dateFormatted' => $m->starts_at->diffForHumans(),
                        'icon' => 'droplets',
                        'color' => 'sky',
                    ]);
                });
        }

        if (class_exists(EngineeringOffice::class)) {
            EngineeringOffice::where('approval_status', 'approved')
                ->where('expires_at', '<=', now()->addMonth()->toDateString())
                ->where('expires_at', '>=', now()->toDateString())
                ->orderBy('expires_at')
                ->take(5)
                ->get()
                ->each(function ($office) use ($events) {
                    $events->push([
                        'type' => 'expiry',
                        'title' => $office->office_name.' - انتهاء اعتماد',
                        'date' => $office->expires_at->format('Y-m-d'),
                        'dateFormatted' => $office->expires_at->diffForHumans(),
                        'icon' => 'hard-hat',
                        'color' => 'red',
                    ]);
                });
        }

        return $events->sortBy('date')->take(10)->values()->toArray();
    }

    private function getNotifications(): array
    {
        $notifications = [];

        if (class_exists(EngineeringOffice::class)) {
            $pendingOffices = EngineeringOffice::where('approval_status', 'pending')->count();
            if ($pendingOffices > 0) {
                $notifications[] = [
                    'message' => "يوجد {$pendingOffices} مكتب هندسي بانتظار الاعتماد",
                    'icon' => 'hard-hat',
                    'color' => 'warning',
                ];
            }
        }

        if (class_exists(Job::class)) {
            $draftJobs = Job::where('status', 'draft')->count();
            if ($draftJobs > 0) {
                $notifications[] = [
                    'message' => "يوجد {$draftJobs} وظيفة في المسودة",
                    'icon' => 'briefcase',
                    'color' => 'info',
                ];
            }

            $closingSoon = Job::where('status', 'published')
                ->where('closing_at', '>=', now()->toDateString())
                ->where('closing_at', '<=', now()->addDays(3)->toDateString())
                ->count();
            if ($closingSoon > 0) {
                $notifications[] = [
                    'message' => "{$closingSoon} وظيفة ستنتهي خلال 3 أيام",
                    'icon' => 'clock',
                    'color' => 'danger',
                ];
            }
        }

        if (class_exists(Facility::class)) {
            $draftFacilities = Facility::where('status', 'draft')->count();
            if ($draftFacilities > 0) {
                $notifications[] = [
                    'message' => "يوجد {$draftFacilities} مرفق عام في المسودة",
                    'icon' => 'building-2',
                    'color' => 'info',
                ];
            }
        }

        if (class_exists(ElectronicService::class)) {
            $draftServices = ElectronicService::where('status', 'draft')->count();
            if ($draftServices > 0) {
                $notifications[] = [
                    'message' => "يوجد {$draftServices} خدمة إلكترونية في المسودة",
                    'icon' => 'laptop',
                    'color' => 'info',
                ];
            }
        }

        return $notifications;
    }

    private function formatBytes(int $bytes, int $precision = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        if ($bytes === 0) {
            return '0 B';
        }

        $i = (int) floor(log($bytes, 1024));

        return round($bytes / (1024 ** $i), $precision).' '.$units[$i];
    }
}
