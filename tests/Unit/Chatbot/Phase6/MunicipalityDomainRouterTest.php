<?php

declare(strict_types=1);

use App\Domains\Chatbot\DTOs\ConversationStateData;
use App\Domains\Chatbot\Enums\ChatbotIntent;
use App\Domains\Chatbot\Services\MunicipalityDomainRouter;

beforeEach(function (): void {
    $this->router = new MunicipalityDomainRouter;
});

it('routes greeting to general domain', function (): void {
    $route = $this->router->route(ChatbotIntent::Greeting, 'مرحبا', new ConversationStateData);
    expect($route->domain)->toBe('general');
    expect($route->isElectronicServices())->toBeFalse();
    expect($route->isMunicipalityDomain())->toBeFalse();
});

it('routes thanks to general domain', function (): void {
    $route = $this->router->route(ChatbotIntent::Thanks, 'شكرا', new ConversationStateData);
    expect($route->domain)->toBe('general');
});

it('routes service intents to electronic_services', function (): void {
    $route = $this->router->route(ChatbotIntent::ServiceSearch, 'بدي رخصة بناء', new ConversationStateData);
    expect($route->domain)->toBe('electronic_services');
    expect($route->isElectronicServices())->toBeTrue();
});

it('routes municipality_phone to municipality_info domain', function (): void {
    $route = $this->router->route(ChatbotIntent::MunicipalityPhone, 'شو رقم البلدية', new ConversationStateData);
    expect($route->domain)->toBe('municipality_info');
    expect($route->handlerKey)->toBe('municipality_phone');
    expect($route->isMunicipalityDomain())->toBeTrue();
});

it('routes municipality_address to municipality_info domain', function (): void {
    $route = $this->router->route(ChatbotIntent::MunicipalityAddress, 'وين البلدية', new ConversationStateData);
    expect($route->domain)->toBe('municipality_info');
});

it('routes municipality_working_hours to municipality_info domain', function (): void {
    $route = $this->router->route(ChatbotIntent::MunicipalityWorkingHours, 'متى الدوام', new ConversationStateData);
    expect($route->domain)->toBe('municipality_info');
});

it('routes municipality_about to municipality_info domain', function (): void {
    $route = $this->router->route(ChatbotIntent::MunicipalityAbout, 'عن البلدية', new ConversationStateData);
    expect($route->domain)->toBe('municipality_info');
});

it('routes departments_list to departments domain', function (): void {
    $route = $this->router->route(ChatbotIntent::DepartmentsList, 'شو الأقسام', new ConversationStateData);
    expect($route->domain)->toBe('departments');
});

it('routes water_schedule to water_schedule domain', function (): void {
    $route = $this->router->route(ChatbotIntent::WaterSchedule, 'متى المي', new ConversationStateData);
    expect($route->domain)->toBe('water_schedule');
    expect($route->requiresEntity)->toBeTrue();
    expect($route->requiredEntityType)->toBe('water_area');
});

it('routes jobs_open to jobs domain', function (): void {
    $route = $this->router->route(ChatbotIntent::JobsOpen, 'في وظائف', new ConversationStateData);
    expect($route->domain)->toBe('jobs');
    expect($route->requiresEntity)->toBeFalse();
});

it('routes latest_news to news domain', function (): void {
    $route = $this->router->route(ChatbotIntent::LatestNews, 'آخر الأخبار', new ConversationStateData);
    expect($route->domain)->toBe('news');
});

it('routes latest_announcements to announcements domain', function (): void {
    $route = $this->router->route(ChatbotIntent::LatestAnnouncements, 'آخر الإعلانات', new ConversationStateData);
    expect($route->domain)->toBe('announcements');
});

it('routes latest_council_decisions to council_decisions domain', function (): void {
    $route = $this->router->route(ChatbotIntent::LatestCouncilDecisions, 'آخر القرارات', new ConversationStateData);
    expect($route->domain)->toBe('council_decisions');
});

it('routes facilities_list to facilities domain', function (): void {
    $route = $this->router->route(ChatbotIntent::FacilitiesList, 'المرافق', new ConversationStateData);
    expect($route->domain)->toBe('facilities');
});

it('routes engineering_offices_list to engineering_offices domain', function (): void {
    $route = $this->router->route(ChatbotIntent::EngineeringOfficesList, 'المكاتب الهندسية', new ConversationStateData);
    expect($route->domain)->toBe('engineering_offices');
});

it('routes council_members_list to council_members domain', function (): void {
    $route = $this->router->route(ChatbotIntent::CouncilMembersList, 'أعضاء المجلس', new ConversationStateData);
    expect($route->domain)->toBe('council_members');
});

it('routing is deterministic', function (): void {
    $route1 = $this->router->route(ChatbotIntent::WaterSchedule, 'متى المي', new ConversationStateData);
    $route2 = $this->router->route(ChatbotIntent::WaterSchedule, 'متى المي', new ConversationStateData);

    expect($route1->domain)->toBe($route2->domain);
    expect($route1->handlerKey)->toBe($route2->handlerKey);
});

it('sets requiresEntity for search intents', function (): void {
    $searchIntents = [
        ChatbotIntent::DepartmentSearch,
        ChatbotIntent::JobSearch,
        ChatbotIntent::NewsSearch,
        ChatbotIntent::FacilitySearch,
        ChatbotIntent::EngineeringOfficeSearch,
        ChatbotIntent::CouncilMemberSearch,
    ];

    foreach ($searchIntents as $intent) {
        $route = $this->router->route($intent, 'بحث', new ConversationStateData);
        expect($route->requiresEntity)->toBeTrue("{$intent->value} should require entity");
    }
});

it('does not set requiresEntity for list intents', function (): void {
    $listIntents = [
        ChatbotIntent::DepartmentsList,
        ChatbotIntent::FacilitiesList,
        ChatbotIntent::EngineeringOfficesList,
        ChatbotIntent::CouncilMembersList,
    ];

    foreach ($listIntents as $intent) {
        $route = $this->router->route($intent, 'قائمة', new ConversationStateData);
        expect($route->requiresEntity)->toBeFalse("{$intent->value} should not require entity");
    }
});

it('all domain routes have explanation', function (): void {
    foreach (ChatbotIntent::cases() as $intent) {
        if ($intent === ChatbotIntent::Unknown) {
            continue;
        }
        $route = $this->router->route($intent, 'test', new ConversationStateData);
        expect($route->explanation)->not->toBeEmpty("{$intent->value} should have explanation");
    }
});

it('static isNonServiceIntent works correctly', function (): void {
    expect(MunicipalityDomainRouter::isNonServiceIntent(ChatbotIntent::MunicipalityPhone))->toBeTrue();
    expect(MunicipalityDomainRouter::isNonServiceIntent(ChatbotIntent::WaterSchedule))->toBeTrue();
    expect(MunicipalityDomainRouter::isNonServiceIntent(ChatbotIntent::ServiceFees))->toBeFalse();
    expect(MunicipalityDomainRouter::isNonServiceIntent(ChatbotIntent::Greeting))->toBeFalse();
});
