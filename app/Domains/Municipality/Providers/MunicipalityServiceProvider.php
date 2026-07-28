<?php

declare(strict_types=1);

namespace App\Domains\Municipality\Providers;

use App\Domains\Municipality\Actions\ArchiveCouncilDecisionAction;
use App\Domains\Municipality\Actions\CancelCouncilDecisionAction;
use App\Domains\Municipality\Actions\CreateCouncilDecisionAction;
use App\Domains\Municipality\Actions\CreateCouncilMemberAction;
use App\Domains\Municipality\Actions\DeleteContactAction;
use App\Domains\Municipality\Actions\DeleteCouncilDecisionAction;
use App\Domains\Municipality\Actions\DeleteCouncilMemberAction;
use App\Domains\Municipality\Actions\DeleteCustomFieldAction;
use App\Domains\Municipality\Actions\DeleteExternalPlatformAction;
use App\Domains\Municipality\Actions\DeleteSocialPlatformAction;
use App\Domains\Municipality\Actions\PublishCouncilDecisionAction;
use App\Domains\Municipality\Actions\ReorderCouncilMembersAction;
use App\Domains\Municipality\Actions\SaveContactAction;
use App\Domains\Municipality\Actions\SaveCustomFieldAction;
use App\Domains\Municipality\Actions\SaveExternalPlatformAction;
use App\Domains\Municipality\Actions\SaveGeneralInfoAction;
use App\Domains\Municipality\Actions\SaveSocialPlatformAction;
use App\Domains\Municipality\Actions\ToggleFeaturedCouncilMemberAction;
use App\Domains\Municipality\Actions\TogglePublicCouncilMemberAction;
use App\Domains\Municipality\Actions\UpdateCouncilDecisionAction;
use App\Domains\Municipality\Actions\UpdateCouncilMemberAction;
use App\Domains\Municipality\Contracts\CouncilDecisionRepositoryInterface;
use App\Domains\Municipality\Contracts\CouncilMemberRepositoryInterface;
use App\Domains\Municipality\Contracts\MunicipalityRepositoryInterface;
use App\Domains\Municipality\Models\CouncilDecision;
use App\Domains\Municipality\Models\CouncilMember;
use App\Domains\Municipality\Models\Municipality;
use App\Domains\Municipality\Policies\CouncilDecisionPolicy;
use App\Domains\Municipality\Policies\CouncilMemberPolicy;
use App\Domains\Municipality\Policies\MunicipalityPolicy;
use App\Domains\Municipality\Repositories\EloquentCouncilDecisionRepository;
use App\Domains\Municipality\Repositories\EloquentCouncilMemberRepository;
use App\Domains\Municipality\Repositories\EloquentMunicipalityRepository;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

final class MunicipalityServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(MunicipalityRepositoryInterface::class, EloquentMunicipalityRepository::class);
        $this->app->bind(CouncilDecisionRepositoryInterface::class, EloquentCouncilDecisionRepository::class);
        $this->app->bind(CouncilMemberRepositoryInterface::class, EloquentCouncilMemberRepository::class);

        $this->app->singleton(SaveGeneralInfoAction::class);
        $this->app->singleton(SaveContactAction::class);
        $this->app->singleton(DeleteContactAction::class);
        $this->app->singleton(SaveSocialPlatformAction::class);
        $this->app->singleton(DeleteSocialPlatformAction::class);
        $this->app->singleton(SaveExternalPlatformAction::class);
        $this->app->singleton(DeleteExternalPlatformAction::class);
        $this->app->singleton(SaveCustomFieldAction::class);
        $this->app->singleton(DeleteCustomFieldAction::class);

        $this->app->singleton(CreateCouncilDecisionAction::class);
        $this->app->singleton(UpdateCouncilDecisionAction::class);
        $this->app->singleton(DeleteCouncilDecisionAction::class);
        $this->app->singleton(PublishCouncilDecisionAction::class);
        $this->app->singleton(ArchiveCouncilDecisionAction::class);
        $this->app->singleton(CancelCouncilDecisionAction::class);

        $this->app->singleton(CreateCouncilMemberAction::class);
        $this->app->singleton(UpdateCouncilMemberAction::class);
        $this->app->singleton(DeleteCouncilMemberAction::class);
        $this->app->singleton(ToggleFeaturedCouncilMemberAction::class);
        $this->app->singleton(TogglePublicCouncilMemberAction::class);
        $this->app->singleton(ReorderCouncilMembersAction::class);
    }

    public function boot(): void
    {
        Gate::policy(Municipality::class, MunicipalityPolicy::class);
        Gate::policy(CouncilDecision::class, CouncilDecisionPolicy::class);
        Gate::policy(CouncilMember::class, CouncilMemberPolicy::class);
    }
}
