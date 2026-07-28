<?php

declare(strict_types=1);

use App\Livewire\Auth\ChangePassword;
use App\Livewire\Auth\ForgotPassword;
use App\Livewire\Auth\Login;
use App\Livewire\Auth\ResetPassword;
use App\Livewire\ElectronicServices\ElectronicServiceAnalytics;
use App\Livewire\ElectronicServices\ElectronicServiceForm;
use App\Livewire\ElectronicServices\ElectronicServicesIndex;
use App\Livewire\ElectronicServices\ElectronicServiceShow;
use App\Livewire\Department\PublicDepartmentsPortal;
use App\Livewire\Department\PublicDepartmentShow;
use App\Livewire\ElectronicServices\PublicServiceDetail;
use App\Livewire\ElectronicServices\PublicServicesCategory;
use App\Livewire\ElectronicServices\PublicServicesPortal;
use App\Livewire\ElectronicServices\ServiceCategoriesIndex;
use App\Livewire\ElectronicServices\ServiceCategoryForm;
use App\Livewire\ElectronicServices\ServiceCategoryShow;
use App\Livewire\EngineeringOffices\EngineeringOfficeForm;
use App\Livewire\EngineeringOffices\EngineeringOfficesIndex;
use App\Livewire\EngineeringOffices\EngineeringOfficeShow;
use App\Livewire\Dashboard\ExecutiveDashboard;
use App\Livewire\Homepage\HomepageDashboard;
use App\Livewire\Homepage\HomepageSettingsForm;
use App\Livewire\Homepage\HomepageQuickLinksIndex;
use App\Livewire\Homepage\HomepageQuickLinkForm;
use App\Livewire\Homepage\HomepageSectionsManager;
use App\Livewire\Homepage\HomepageSlidesIndex;
use App\Livewire\Homepage\HomepageSlideForm;
use App\Livewire\Homepage\HomepageStatisticsIndex;
use App\Livewire\Homepage\HomepageStatisticForm;
use App\Livewire\Homepage\PublicHomePage;
use App\Livewire\PageCarousels\PageCarouselsIndex;
use App\Livewire\PageCarousels\PageCarouselForm;
use App\Livewire\Admin\Announcements\AnnouncementsIndex;
use App\Livewire\Admin\Announcements\AnnouncementForm;
use App\Livewire\Jobs\JobForm;
use App\Livewire\Jobs\JobsIndex;
use App\Livewire\Announcements\PublicAnnouncementsIndex;
use App\Livewire\Announcements\PublicAnnouncementShow;
use App\Livewire\Jobs\PublicJobShow;
use App\Livewire\Jobs\PublicJobsIndex;
use App\Livewire\PublicFacilities\FacilitiesIndex;
use App\Livewire\PublicFacilities\FacilityCategoriesForm;
use App\Livewire\PublicFacilities\FacilityCategoriesIndex;
use App\Livewire\PublicFacilities\FacilityForm;
use App\Livewire\PublicFacilities\PublicFacilitiesIndex;
use App\Livewire\PublicFacilities\PublicFacilityShow;
use App\Livewire\Roles\RoleIndex;
use App\Livewire\WaterSchedule\PublicWaterSchedule;
use App\Livewire\WaterSchedule\WaterAreasForm;
use App\Livewire\WaterSchedule\WaterAreasIndex;
use App\Livewire\WaterSchedule\WaterMaintenanceForm;
use App\Livewire\WaterSchedule\WaterMaintenanceIndex;
use App\Livewire\WaterSchedule\WaterScheduleDashboard;
use App\Livewire\Users\UserIndex;
use App\Livewire\News\NewsIndex;
use App\Livewire\News\NewsForm;
use App\Livewire\Projects\ProjectsIndex;
use App\Livewire\Projects\ProjectForm;
use App\Livewire\Complaints\ComplaintsIndex;
use App\Livewire\Complaints\ComplaintForm;
use App\Livewire\Tenders\TendersIndex;
use App\Livewire\Tenders\TenderForm;
use App\Livewire\News\PublicNewsIndex;
use App\Livewire\News\PublicNewsShow;
use App\Livewire\Projects\PublicProjectsIndex;
use App\Livewire\Projects\PublicProjectShow;
use App\Livewire\Complaints\PublicComplaintForm;
use App\Livewire\Complaints\PublicComplaintTracking;
use App\Livewire\Tenders\PublicTendersIndex;
use App\Livewire\Tenders\PublicTenderShow;
use Illuminate\Support\Facades\Route;

Route::get('/', PublicHomePage::class)->name('home');

// Guest routes
Route::middleware('guest')->group(function (): void {
    Route::get('login', Login::class)->middleware('throttle:login')->name('login');
    Route::get('forgot-password', ForgotPassword::class)->name('password.request');
    Route::get('reset-password/{token}/{email}', ResetPassword::class)->name('password.reset');
});

// Public Jobs
Route::get('jobs', PublicJobsIndex::class)->name('public.jobs.index');
Route::get('jobs/{job:slug}', PublicJobShow::class)->name('public.jobs.show');

// Public Water Schedule
Route::get('water-schedule', PublicWaterSchedule::class)->name('public.water-schedule');

// Public Facilities
Route::get('facilities', PublicFacilitiesIndex::class)->name('public.facilities.index');
Route::get('facilities/{facility:slug}', PublicFacilityShow::class)->name('public.facilities.show');

// Public Services Portal
Route::get('services', PublicServicesPortal::class)->name('public.services.index');
Route::get('services/{category:slug}', PublicServicesCategory::class)->name('public.services.category');
Route::get('services/{category:slug}/{service:slug}', PublicServiceDetail::class)->name('public.services.show');

// Public Departments Portal
Route::get('departments', PublicDepartmentsPortal::class)->name('public.departments.index');
Route::get('departments/{department:slug}', PublicDepartmentShow::class)->name('public.departments.show');

// Public Engineering Offices (Public)
Route::get('engineering-offices', \App\Livewire\EngineeringOffices\PublicEngineeringOfficesIndex::class)->name('public.engineering-offices.index');
Route::get('engineering-offices/{office:slug}', \App\Livewire\EngineeringOffices\PublicEngineeringOfficeShow::class)->name('public.engineering-offices.show');

// Public Open Data
Route::get('open-data', \App\Livewire\OpenData\OpenDataIndex::class)->name('public.open-data.index');

// Public Municipality About Page
Route::get('about', \App\Livewire\Municipality\PublicMunicipalityAbout::class)->name('public.municipality.about');

// Public Council Decisions (must be before council/{councilMember:slug})
Route::get('council/decisions', \App\Livewire\Council\PublicCouncilDecisionsIndex::class)->name('public.council.decisions.index');
Route::get('council/decisions/{decision}', \App\Livewire\Council\PublicCouncilDecisionShow::class)->name('public.council.decisions.show');

// Public Council
Route::get('council', \App\Livewire\Council\PublicCouncilMembersPortal::class)->name('public.council.index');
Route::get('council/{councilMember:slug}', \App\Livewire\Council\PublicCouncilMemberProfile::class)->name('public.council.show');

// Public Announcements
Route::get('announcements', PublicAnnouncementsIndex::class)->name('public.announcements.index');
Route::get('announcements/{announcement:slug}', PublicAnnouncementShow::class)->name('public.announcements.show');

// Public News
Route::get('news', PublicNewsIndex::class)->name('public.news.index');
Route::get('news/{newsItem:slug}', PublicNewsShow::class)->name('public.news.show');

// Public Projects
Route::get('projects', PublicProjectsIndex::class)->name('public.projects.index');
Route::get('projects/{project:slug}', PublicProjectShow::class)->name('public.projects.show');

// Public Complaints
Route::get('complaints/submit', PublicComplaintForm::class)->name('public.complaints.submit');
Route::get('complaints/track', PublicComplaintTracking::class)->name('public.complaints.track');

// Public Tenders
Route::get('tenders', PublicTendersIndex::class)->name('public.tenders.index');
Route::get('tenders/{tender:slug}', PublicTenderShow::class)->name('public.tenders.show');

// Authenticated routes
Route::middleware('auth')->group(function (): void {
    Route::post('logout', function () {
        app(\App\Domains\Authentication\Actions\LogoutAction::class)->execute();

        return redirect()->route('login');
    })->name('logout');

    Route::get('dashboard', ExecutiveDashboard::class)->name('dashboard');

    Route::get('change-password', ChangePassword::class)->name('password.change');

    // Users Management
    Route::middleware('permission:view users')->group(function (): void {
        Route::get('users', UserIndex::class)->name('users.index');
    });

    // Roles Management
    Route::middleware('permission:view roles')->group(function (): void {
        Route::get('roles', RoleIndex::class)->name('roles.index');
    });

    // Departments
    Route::middleware('permission:departments.create')->group(function (): void {
        Route::get('dashboard/departments/create', \App\Livewire\Department\DepartmentForm::class)->name('dashboard.departments.create');
    });

    Route::middleware('permission:departments.update')->group(function (): void {
        Route::get('dashboard/departments/{department}/edit', \App\Livewire\Department\DepartmentForm::class)->name('dashboard.departments.edit');
    });

    Route::middleware('permission:departments.view')->group(function (): void {
        Route::get('dashboard/departments', \App\Livewire\Department\DepartmentsIndex::class)->name('dashboard.departments');
        Route::get('dashboard/departments/{department}', \App\Livewire\Department\DepartmentShow::class)->name('dashboard.departments.show');
    });

    // Electronic Services - Categories
    Route::middleware('permission:service_categories.create')->group(function (): void {
        Route::get('electronic-services/categories/create', ServiceCategoryForm::class)->name('dashboard.electronic-services.categories.create');
    });

    Route::middleware('permission:service_categories.update')->group(function (): void {
        Route::get('electronic-services/categories/{category}/edit', ServiceCategoryForm::class)->name('dashboard.electronic-services.categories.edit');
    });

    Route::middleware('permission:service_categories.view')->group(function (): void {
        Route::get('electronic-services/categories', ServiceCategoriesIndex::class)->name('dashboard.electronic-services.categories');
        Route::get('electronic-services/categories/{category}', ServiceCategoryShow::class)->name('dashboard.electronic-services.categories.show');
    });

    // Engineering Offices
    Route::middleware('permission:engineering_offices.create')->group(function (): void {
        Route::get('engineering-offices/create', EngineeringOfficeForm::class)->name('dashboard.engineering-offices.create');
    });

    Route::middleware('permission:engineering_offices.update')->group(function (): void {
        Route::get('engineering-offices/{office}/edit', EngineeringOfficeForm::class)->name('dashboard.engineering-offices.edit');
    });

    Route::middleware('permission:engineering_offices.view')->group(function (): void {
        Route::get('engineering-offices', EngineeringOfficesIndex::class)->name('dashboard.engineering-offices');
        Route::get('engineering-offices/{office}', EngineeringOfficeShow::class)->name('dashboard.engineering-offices.show');
    });

    // Electronic Services - Services
    Route::middleware('permission:electronic_services.create')->group(function (): void {
        Route::get('electronic-services/services/create', ElectronicServiceForm::class)->name('dashboard.electronic-services.services.create');
    });

    Route::middleware('permission:electronic_services.update')->group(function (): void {
        Route::get('electronic-services/services/{service}/edit', ElectronicServiceForm::class)->name('dashboard.electronic-services.services.edit');
    });

    Route::middleware('permission:electronic_services.view')->group(function (): void {
        Route::get('electronic-services/services', ElectronicServicesIndex::class)->name('dashboard.electronic-services.services');
        Route::get('electronic-services/services/{service}', ElectronicServiceShow::class)->name('dashboard.electronic-services.services.show');
    });

    // Electronic Services - Analytics
    Route::middleware('permission:electronic_services.analytics')->group(function (): void {
        Route::get('electronic-services/analytics', ElectronicServiceAnalytics::class)->name('dashboard.electronic-services.analytics');
    });

    // Legacy dashboard services (moved to avoid conflict with public /services)
    Route::middleware('permission:view services')->group(function (): void {
        Route::get('dashboard/services', fn () => view('dashboard.services'))->name('dashboard.services.index');
    });

    // News Management
    Route::middleware('permission:news.create')->group(function (): void {
        Route::get('dashboard/news/create', NewsForm::class)->name('dashboard.news.create');
    });

    Route::middleware('permission:news.update')->group(function (): void {
        Route::get('dashboard/news/{newsItem}/edit', NewsForm::class)->name('dashboard.news.edit');
    });

    Route::middleware('permission:news.view')->group(function (): void {
        Route::get('dashboard/news', NewsIndex::class)->name('dashboard.news');
    });

    // Projects Management
    Route::middleware('permission:projects.create')->group(function (): void {
        Route::get('dashboard/projects/create', ProjectForm::class)->name('dashboard.projects.create');
    });

    Route::middleware('permission:projects.update')->group(function (): void {
        Route::get('dashboard/projects/{project}/edit', ProjectForm::class)->name('dashboard.projects.edit');
    });

    Route::middleware('permission:projects.view')->group(function (): void {
        Route::get('dashboard/projects', ProjectsIndex::class)->name('dashboard.projects');
    });

    // Complaints Management
    Route::middleware('permission:complaints.create')->group(function (): void {
        Route::get('dashboard/complaints/create', ComplaintForm::class)->name('dashboard.complaints.create');
    });

    Route::middleware('permission:complaints.update')->group(function (): void {
        Route::get('dashboard/complaints/{complaint}/edit', ComplaintForm::class)->name('dashboard.complaints.edit');
    });

    Route::middleware('permission:complaints.view')->group(function (): void {
        Route::get('dashboard/complaints', ComplaintsIndex::class)->name('dashboard.complaints');
    });

    // Tenders Management
    Route::middleware('permission:tenders.create')->group(function (): void {
        Route::get('dashboard/tenders/create', TenderForm::class)->name('dashboard.tenders.create');
    });

    Route::middleware('permission:tenders.update')->group(function (): void {
        Route::get('dashboard/tenders/{tender}/edit', TenderForm::class)->name('dashboard.tenders.edit');
    });

    Route::middleware('permission:tenders.view')->group(function (): void {
        Route::get('dashboard/tenders', TendersIndex::class)->name('dashboard.tenders');
    });

    // Reports
    Route::middleware('permission:view activity logs')->group(function (): void {
        Route::get('reports', fn () => view('dashboard.reports'))->name('reports.index');
    });

    // Settings
    Route::middleware('permission:view settings')->group(function (): void {
        Route::get('settings', fn () => view('dashboard.settings'))->name('settings.index');
    });

    // Homepage Management
    Route::middleware('permission:homepage.view')->group(function (): void {
        Route::get('homepage', HomepageDashboard::class)->name('dashboard.homepage');
    });

    Route::middleware('permission:homepage.update')->group(function (): void {
        Route::get('homepage/settings', HomepageSettingsForm::class)->name('dashboard.homepage.settings');
    });

    Route::middleware('permission:homepage.slides.view')->group(function (): void {
        Route::get('homepage/slides', HomepageSlidesIndex::class)->name('dashboard.homepage.slides');
    });

    Route::middleware('permission:homepage.slides.create')->group(function (): void {
        Route::get('homepage/slides/create', HomepageSlideForm::class)->name('dashboard.homepage.slides.create');
    });

    Route::middleware('permission:homepage.slides.update')->group(function (): void {
        Route::get('homepage/slides/{slide}/edit', HomepageSlideForm::class)->name('dashboard.homepage.slides.edit');
    });

    Route::middleware('permission:homepage.sections.update')->group(function (): void {
        Route::get('homepage/sections', HomepageSectionsManager::class)->name('dashboard.homepage.sections');
    });

    Route::middleware('permission:homepage.quick_links.view')->group(function (): void {
        Route::get('homepage/quick-links', HomepageQuickLinksIndex::class)->name('dashboard.homepage.quick-links');
    });

    Route::middleware('permission:homepage.quick_links.create')->group(function (): void {
        Route::get('homepage/quick-links/create', HomepageQuickLinkForm::class)->name('dashboard.homepage.quick-links.create');
    });

    Route::middleware('permission:homepage.quick_links.update')->group(function (): void {
        Route::get('homepage/quick-links/{quickLink}/edit', HomepageQuickLinkForm::class)->name('dashboard.homepage.quick-links.edit');
    });

    Route::middleware('permission:homepage.statistics.view')->group(function (): void {
        Route::get('homepage/statistics', HomepageStatisticsIndex::class)->name('dashboard.homepage.statistics');
    });

    Route::middleware('permission:homepage.statistics.create')->group(function (): void {
        Route::get('homepage/statistics/create', HomepageStatisticForm::class)->name('dashboard.homepage.statistics.create');
    });

    Route::middleware('permission:homepage.statistics.update')->group(function (): void {
        Route::get('homepage/statistics/{statistic}/edit', HomepageStatisticForm::class)->name('dashboard.homepage.statistics.edit');
    });

    // Page Carousels
    Route::middleware('permission:homepage.slides.view')->group(function (): void {
        Route::get('page-carousels', PageCarouselsIndex::class)->name('dashboard.page-carousels');
    });

    Route::middleware('permission:homepage.slides.create')->group(function (): void {
        Route::get('page-carousels/create', PageCarouselForm::class)->name('dashboard.page-carousels.create');
    });

    Route::middleware('permission:homepage.slides.update')->group(function (): void {
        Route::get('page-carousels/{slide}/edit', PageCarouselForm::class)->name('dashboard.page-carousels.edit');
    });

    // Jobs
    Route::middleware('permission:jobs.create')->group(function (): void {
        Route::get('dashboard/jobs/create', JobForm::class)->name('dashboard.jobs.create');
    });

    Route::middleware('permission:jobs.update')->group(function (): void {
        Route::get('dashboard/jobs/{job}/edit', JobForm::class)->name('dashboard.jobs.edit');
    });

    Route::middleware('permission:jobs.view')->group(function (): void {
        Route::get('dashboard/jobs', JobsIndex::class)->name('dashboard.jobs');
    });

    // Announcements
    Route::middleware('permission:announcements.create')->group(function (): void {
        Route::get('dashboard/announcements/create', AnnouncementForm::class)->name('dashboard.announcements.create');
    });

    Route::middleware('permission:announcements.update')->group(function (): void {
        Route::get('dashboard/announcements/{announcement}/edit', AnnouncementForm::class)->name('dashboard.announcements.edit');
    });

    Route::middleware('permission:announcements.view')->group(function (): void {
        Route::get('dashboard/announcements', AnnouncementsIndex::class)->name('dashboard.announcements');
    });

    // Water Schedule
    Route::middleware('permission:water.create')->group(function (): void {
        Route::get('water-schedule/areas/create', WaterAreasForm::class)->name('dashboard.water-schedule.areas.create');
        Route::get('water-schedule/maintenance/create', WaterMaintenanceForm::class)->name('dashboard.water-schedule.maintenance.create');
    });

    Route::middleware('permission:water.update')->group(function (): void {
        Route::get('water-schedule/areas/{waterArea}/edit', WaterAreasForm::class)->name('dashboard.water-schedule.areas.edit');
        Route::get('water-schedule/maintenance/{maintenance}/edit', WaterMaintenanceForm::class)->name('dashboard.water-schedule.maintenance.edit');
    });

    Route::middleware('permission:water.view')->group(function (): void {
        Route::get('water-schedule', WaterScheduleDashboard::class)->name('dashboard.water-schedule');
        Route::get('water-schedule/areas', WaterAreasIndex::class)->name('dashboard.water-schedule.areas');
        Route::get('water-schedule/maintenance', WaterMaintenanceIndex::class)->name('dashboard.water-schedule.maintenance');
    });

    // Public Facilities
    Route::middleware('permission:facility_categories.create')->group(function (): void {
        Route::get('dashboard/facilities/categories/create', FacilityCategoriesForm::class)->name('dashboard.facilities.categories.create');
    });

    Route::middleware('permission:facility_categories.update')->group(function (): void {
        Route::get('dashboard/facilities/categories/{category}/edit', FacilityCategoriesForm::class)->name('dashboard.facilities.categories.edit');
    });

    Route::middleware('permission:facility_categories.view')->group(function (): void {
        Route::get('dashboard/facilities/categories', FacilityCategoriesIndex::class)->name('dashboard.facilities.categories');
    });

    Route::middleware('permission:facilities.create')->group(function (): void {
        Route::get('dashboard/facilities/create', FacilityForm::class)->name('dashboard.facilities.create');
    });

    Route::middleware('permission:facilities.update')->group(function (): void {
        Route::get('dashboard/facilities/{facility}/edit', FacilityForm::class)->name('dashboard.facilities.edit');
    });

    Route::middleware('permission:facilities.view')->group(function (): void {
        Route::get('dashboard/facilities', FacilitiesIndex::class)->name('dashboard.facilities');
    });

    // Municipality
    Route::middleware('permission:municipality.view')->group(function (): void {
        Route::get('dashboard/municipality', \App\Livewire\Municipality\MunicipalityIndex::class)->name('dashboard.municipality.index');
    });

    Route::middleware('permission:municipality.update')->group(function (): void {
        Route::get('dashboard/municipality/general-info', \App\Livewire\Municipality\MunicipalityGeneralInfo::class)->name('dashboard.municipality.general-info');
    });

    Route::middleware('permission:municipality.contacts.manage')->group(function (): void {
        Route::get('dashboard/municipality/contacts', \App\Livewire\Municipality\MunicipalityContacts::class)->name('dashboard.municipality.contacts');
    });

    Route::middleware('permission:municipality.social.manage')->group(function (): void {
        Route::get('dashboard/municipality/social', \App\Livewire\Municipality\MunicipalitySocial::class)->name('dashboard.municipality.social');
    });

    Route::middleware('permission:municipality.platforms.manage')->group(function (): void {
        Route::get('dashboard/municipality/platforms', \App\Livewire\Municipality\MunicipalityPlatforms::class)->name('dashboard.municipality.platforms');
    });

    Route::middleware('permission:municipality.custom-fields.manage')->group(function (): void {
        Route::get('dashboard/municipality/custom-fields', \App\Livewire\Municipality\MunicipalityCustomFields::class)->name('dashboard.municipality.custom-fields');
    });

    Route::middleware('permission:municipality.media.manage')->group(function (): void {
        Route::get('dashboard/municipality/media', \App\Livewire\Municipality\MunicipalityMedia::class)->name('dashboard.municipality.media');
    });

    Route::middleware('permission:municipality.business-hours.manage')->group(function (): void {
        Route::get('dashboard/municipality/business-hours', \App\Livewire\Municipality\MunicipalityBusinessHours::class)->name('dashboard.municipality.business-hours');
    });

    Route::middleware('permission:municipality.emergency-contacts.manage')->group(function (): void {
        Route::get('dashboard/municipality/emergency-contacts', \App\Livewire\Municipality\MunicipalityEmergencyContacts::class)->name('dashboard.municipality.emergency-contacts');
    });

    // Council Decisions
    Route::middleware('permission:council_decisions.create')->group(function (): void {
        Route::get('dashboard/municipality/council-decisions/create', \App\Livewire\Municipality\CouncilDecisionForm::class)->name('dashboard.municipality.council-decisions.create');
    });

    Route::middleware('permission:council_decisions.update')->group(function (): void {
        Route::get('dashboard/municipality/council-decisions/{councilDecision}/edit', \App\Livewire\Municipality\CouncilDecisionForm::class)->name('dashboard.municipality.council-decisions.edit');
    });

    Route::middleware('permission:council_decisions.view')->group(function (): void {
        Route::get('dashboard/municipality/council-decisions', \App\Livewire\Municipality\CouncilDecisionsIndex::class)->name('dashboard.municipality.council-decisions');
        Route::get('dashboard/municipality/council-decisions/{councilDecision}', \App\Livewire\Municipality\CouncilDecisionShow::class)->name('dashboard.municipality.council-decisions.show');
    });

    // Council Members
    Route::middleware('permission:council_members.create')->group(function (): void {
        Route::get('dashboard/municipality/council-members/create', \App\Livewire\Municipality\CouncilMemberForm::class)->name('dashboard.municipality.council-members.create');
    });

    Route::middleware('permission:council_members.update')->group(function (): void {
        Route::get('dashboard/municipality/council-members/{councilMember}/edit', \App\Livewire\Municipality\CouncilMemberForm::class)->name('dashboard.municipality.council-members.edit');
    });

    Route::middleware('permission:council_members.view')->group(function (): void {
        Route::get('dashboard/municipality/council-members', \App\Livewire\Municipality\CouncilMembersIndex::class)->name('dashboard.municipality.council-members');
        Route::get('dashboard/municipality/council-members/{councilMember}', \App\Livewire\Municipality\CouncilMemberProfile::class)->name('dashboard.municipality.council-members.show');
    });

    // Open Data Management
    Route::middleware('permission:open_data.view')->group(function (): void {
        Route::get('dashboard/open-data', \App\Livewire\OpenData\Admin\OpenDataAdminIndex::class)->name('dashboard.open-data');
    });

    Route::middleware('permission:open_data.create')->group(function (): void {
        Route::get('dashboard/open-data/create', \App\Livewire\OpenData\Admin\OpenDataAdminForm::class)->name('dashboard.open-data.create');
    });

    Route::middleware('permission:open_data.update')->group(function (): void {
        Route::get('dashboard/open-data/{dataset}/edit', \App\Livewire\OpenData\Admin\OpenDataAdminForm::class)->name('dashboard.open-data.edit');
    });

    // Debug-only routes — blocked in production
    Route::middleware('can:access panel')->group(function (): void {
        Route::get('setup-database', function () {
            if (!app()->environment('local')) {
                abort(404);
            }

            $output = [];

            try {
                Artisan::call('migrate', ['--force' => true]);
                $output[] = 'Migrations ran.';
            } catch (\Throwable $e) {
                $output[] = 'Migrations error: ' . $e->getMessage();
            }

            try {
                Artisan::call('db:seed', ['--force' => true]);
                $output[] = 'Seeders ran.';
            } catch (\Throwable $e) {
                $output[] = 'Seeders error: ' . $e->getMessage();
            }

            try {
                $users = \App\Domains\Authentication\Models\User::all();
                foreach ($users as $user) {
                    $user->syncRoles(['Super Admin']);
                }
                $output[] = 'Assigned Super Admin role to ' . $users->count() . ' user(s)';
            } catch (\Throwable $e) {
                $output[] = 'Role assignment error: ' . $e->getMessage();
            }

            return nl2br(implode("\n\n", $output));
        })->name('setup.database');

        Route::get('debug-permissions', function () {
            if (!app()->environment('local')) {
                abort(404);
            }

            $user = auth()->user();
            $output = [];

            $output[] = "User: {$user->name} ({$user->email})";
            $output[] = "User ID: {$user->id}";
            $output[] = "Roles: " . $user->getRoleNames()->implode(', ');
            $output[] = "Permissions count: " . \Spatie\Permission\Models\Permission::count();

            return '<pre>' . nl2br(implode("\n", $output)) . '</pre>';
        })->name('debug.permissions');

        Route::get('seed-carousels', function (): string {
            if (!app()->environment('local')) {
                abort(404);
            }

            \Illuminate\Support\Facades\Cache::forget('homepage.public.data');

            foreach (['services','departments','facilities','jobs','council-decisions','council-members','engineering-offices','open-data','water-schedule','announcements'] as $key) {
                \Illuminate\Support\Facades\Cache::forget('page-carousel:' . $key);
            }

            \Illuminate\Support\Facades\DB::table('homepage_slides')->where('page_key', '!=', 'home')->delete();

            $slides = [
                ['page_key' => 'services', 'title' => 'الخدمات الإلكترونية', 'description' => 'جميع الخدمات الإلكترونية.', 'badge_text' => 'الخدمات الإلكترونية', 'is_active' => true, 'sort_order' => 0],
                ['page_key' => 'departments', 'title' => 'دوائر البلدية', 'description' => 'دوائر وأقسام البلدية.', 'badge_text' => 'دوائر البلدية', 'is_active' => true, 'sort_order' => 0],
                ['page_key' => 'facilities', 'title' => 'المرافق العامة', 'description' => 'مرافق بلدية إذنا.', 'badge_text' => 'المرافق العامة', 'is_active' => true, 'sort_order' => 0],
                ['page_key' => 'jobs', 'title' => 'الوظائف', 'description' => 'الفرص الوظيفية.', 'badge_text' => 'الوظائف', 'is_active' => true, 'sort_order' => 0],
                ['page_key' => 'engineering-offices', 'title' => 'المكاتب الهندسية', 'description' => 'المكاتب الهندسية المعتمدة.', 'badge_text' => 'المكاتب الهندسية', 'is_active' => true, 'sort_order' => 0],
                ['page_key' => 'water-schedule', 'title' => 'جدول توزيع المياه', 'description' => 'جدول ضخ المياه.', 'badge_text' => 'جدول المياه', 'is_active' => true, 'sort_order' => 0],
                ['page_key' => 'open-data', 'title' => 'البيانات المفتوحة', 'description' => 'البيانات المفتوحة.', 'badge_text' => 'البيانات المفتوحة', 'is_active' => true, 'sort_order' => 0],
                ['page_key' => 'announcements', 'title' => 'الإعلانات', 'description' => 'الإعلانات الرسمية.', 'badge_text' => 'الإعلانات', 'is_active' => true, 'sort_order' => 0],
            ];

            foreach ($slides as $slide) {
                \Illuminate\Support\Facades\DB::table('homepage_slides')->insert($slide);
            }

            return 'Done.';
        })->name('debug.seed-carousels');
    });
});
