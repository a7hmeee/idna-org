<?php

declare(strict_types=1);

namespace App\Providers;

use App\Domains\Announcements\Providers\AnnouncementServiceProvider;
use App\Domains\Authentication\Providers\AuthenticationServiceProvider;
use App\Domains\Chatbot\Providers\ChatbotServiceProvider;
use App\Domains\ChatbotAnalytics\Providers\ChatbotAnalyticsServiceProvider;
use App\Domains\Complaints\Providers\ComplaintServiceProvider;
use App\Domains\ContactRequests\Providers\ContactRequestServiceProvider;
use App\Domains\CitizenWorkflows\Providers\CitizenWorkflowServiceProvider;
use App\Domains\Department\Providers\DepartmentServiceProvider;
use App\Domains\Dashboard\Providers\DashboardServiceProvider;
use App\Domains\ElectronicServices\Providers\ElectronicServiceProvider;
use App\Domains\EngineeringOffices\Providers\EngineeringOfficeServiceProvider;
use App\Domains\Homepage\Providers\HomepageServiceProvider;
use App\Domains\Jobs\Providers\JobServiceProvider;
use App\Domains\Municipality\Providers\MunicipalityServiceProvider;
use App\Domains\News\Providers\NewsServiceProvider;
use App\Domains\OpenData\Providers\OpenDataServiceProvider;
use App\Domains\Projects\Providers\ProjectServiceProvider;
use App\Domains\PublicFacilities\Providers\PublicFacilitiesServiceProvider;
use App\Domains\RoleManagement\Providers\RoleManagementServiceProvider;
use App\Domains\SharedKernel\Providers\SharedKernelServiceProvider;
use App\Domains\Tenders\Providers\TenderServiceProvider;
use App\Domains\UserManagement\Providers\UserManagementServiceProvider;
use App\Domains\WaterSchedule\Providers\WaterScheduleServiceProvider;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

final class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->register(AnnouncementServiceProvider::class);
        $this->app->register(AuthenticationServiceProvider::class);
        $this->app->register(ChatbotServiceProvider::class);
        $this->app->register(ChatbotAnalyticsServiceProvider::class);
        $this->app->register(ComplaintServiceProvider::class);
        $this->app->register(ContactRequestServiceProvider::class);
        $this->app->register(CitizenWorkflowServiceProvider::class);
        $this->app->register(UserManagementServiceProvider::class);
        $this->app->register(RoleManagementServiceProvider::class);
        $this->app->register(SharedKernelServiceProvider::class);
        $this->app->register(MunicipalityServiceProvider::class);
        $this->app->register(DepartmentServiceProvider::class);
        $this->app->register(DashboardServiceProvider::class);
        $this->app->register(ElectronicServiceProvider::class);
        $this->app->register(EngineeringOfficeServiceProvider::class);
        $this->app->register(HomepageServiceProvider::class);
        $this->app->register(JobServiceProvider::class);
        $this->app->register(NewsServiceProvider::class);
        $this->app->register(OpenDataServiceProvider::class);
        $this->app->register(ProjectServiceProvider::class);
        $this->app->register(PublicFacilitiesServiceProvider::class);
        $this->app->register(TenderServiceProvider::class);
        $this->app->register(WaterScheduleServiceProvider::class);
    }

    public function boot(): void
    {
        $this->configureRateLimiting();

        View::composer('layouts.home', \App\View\Composers\PublicLayoutComposer::class);
    }

    private function configureRateLimiting(): void
    {
        RateLimiter::for('login', function (Request $request) {
            $email = mb_strtolower((string) $request->input('email', ''));
            $key = $email . '|' . ($request->ip() ?? '0.0.0.0');

            return Limit::perMinute(5)->by($key);
        });
    }
}
