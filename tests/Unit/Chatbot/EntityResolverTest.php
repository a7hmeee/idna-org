<?php

declare(strict_types=1);

use App\Domains\Chatbot\Contracts\ChatbotServiceAliasRepositoryInterface;
use App\Domains\Chatbot\Contracts\MunicipalityServiceQueryInterface;
use App\Domains\Chatbot\DTOs\ResolvedServiceData;
use App\Domains\Chatbot\Models\ChatbotServiceAlias;
use App\Domains\Chatbot\Services\ArabicTextNormalizer;
use App\Domains\Chatbot\Services\EntityResolver;
use Illuminate\Database\Eloquent\Collection;

beforeEach(function (): void {
    $this->normalizer = new ArabicTextNormalizer;
    $this->serviceQuery = Mockery::mock(MunicipalityServiceQueryInterface::class);
    $this->aliasRepo = Mockery::mock(ChatbotServiceAliasRepositoryInterface::class);

    // Default expectations: extra resolver calls return null/empty
    $this->serviceQuery->shouldReceive('findPublishedByExactName')
        ->andReturn(null)->byDefault();
    $this->aliasRepo->shouldReceive('findByAlias')
        ->andReturn(null)->byDefault();
    $this->serviceQuery->shouldReceive('findPublishedByText')
        ->andReturn(null)->byDefault();
    $this->aliasRepo->shouldReceive('all')
        ->andReturn(new Collection)->byDefault();

    $this->resolver = new EntityResolver(
        $this->serviceQuery,
        $this->aliasRepo,
        $this->normalizer
    );
});

// =============================================
// Exact Official Name
// =============================================

it('resolves exact official service name', function (): void {
    $service = new ResolvedServiceData(id: 1, name: 'إصدار رخصة بناء');

    $this->serviceQuery->shouldReceive('findPublishedByExactName')
        ->with('اصدار رخصة بناء')
        ->once()
        ->andReturn($service);

    $result = $this->resolver->resolve('اصدار رخصة بناء');

    expect($result)->not->toBeNull();
    expect($result->id)->toBe(1);
    expect($result->name)->toBe('إصدار رخصة بناء');
});

it('resolves exact official name with alef variants', function (): void {
    $service = new ResolvedServiceData(id: 1, name: 'إصدار رخصة بناء');

    $this->serviceQuery->shouldReceive('findPublishedByExactName')
        ->with('اصدار رخصة بناء')
        ->once()
        ->andReturn($service);

    // Input with different alef
    $result = $this->resolver->resolve('اصدار رخصة بناء');

    expect($result)->not->toBeNull();
    expect($result->id)->toBe(1);
});

// =============================================
// Exact Alias
// =============================================

it('resolves exact alias', function (): void {
    $service = new ResolvedServiceData(id: 2, name: 'اشتراك مياه جديد');
    $alias = new ChatbotServiceAlias;
    $alias->alias = 'مياه';
    $alias->service_key = 'اشتراك مياه جديد';
    $alias->is_active = true;

    $input = $this->normalizer->normalize('مياه');

    $this->aliasRepo->shouldReceive('findByAlias')
        ->with($input)
        ->once()
        ->andReturn($alias);

    $this->serviceQuery->shouldReceive('findPublishedByExactName')
        ->with($this->normalizer->normalize('اشتراك مياه جديد'))
        ->once()
        ->andReturn($service);

    $result = $this->resolver->resolve($input);

    expect($result)->not->toBeNull();
    expect($result->id)->toBe(2);
});

// =============================================
// Contained Official Name
// =============================================

it('resolves contained official name', function (): void {
    $service = new ResolvedServiceData(id: 3, name: 'نقل اشتراك مياه');

    $this->serviceQuery->shouldReceive('findPublishedByText')
        ->with('نقل اشتراك')
        ->once()
        ->andReturn($service);

    $result = $this->resolver->resolve('نقل اشتراك');

    expect($result)->not->toBeNull();
    expect($result->id)->toBe(3);
});

// =============================================
// Contained Alias with Ranking
// =============================================

it('ranks contained aliases by longest match first', function (): void {
    $service1 = new ResolvedServiceData(id: 1, name: 'اشتراك مياه جديد');
    $service2 = new ResolvedServiceData(id: 2, name: 'نقل اشتراك مياه');

    $alias1 = new ChatbotServiceAlias;
    $alias1->alias = 'مياه';
    $alias1->service_key = 'اشتراك مياه جديد';
    $alias1->is_active = true;

    $alias2 = new ChatbotServiceAlias;
    $alias2->alias = 'اشتراك مياه';
    $alias2->service_key = 'نقل اشتراك مياه';
    $alias2->is_active = true;

    $this->aliasRepo->shouldReceive('all')
        ->once()
        ->andReturn(new Collection([$alias1, $alias2]));

    $this->serviceQuery->shouldReceive('findPublishedByExactName')
        ->with($this->normalizer->normalize('اشتراك مياه جديد'))
        ->andReturn($service1);

    $this->serviceQuery->shouldReceive('findPublishedByExactName')
        ->with($this->normalizer->normalize('نقل اشتراك مياه'))
        ->andReturn($service2);

    $result = $this->resolver->resolve($this->normalizer->normalize('نقل اشتراك مياه جديد'));

    // Should match 'اشتراك مياه' (longer) over 'مياه'
    expect($result)->not->toBeNull();
    expect($result->id)->toBe(2);
});

it('ranks exact alias match over contained match', function (): void {
    $service1 = new ResolvedServiceData(id: 1, name: 'خدمة مياه');
    $service2 = new ResolvedServiceData(id: 2, name: 'اشتراك مياه جديد');

    $alias1 = new ChatbotServiceAlias;
    $alias1->alias = 'مياه';
    $alias1->service_key = 'خدمة مياه';
    $alias1->is_active = true;

    $alias2 = new ChatbotServiceAlias;
    $alias2->alias = 'اشتراك مياه';
    $alias2->service_key = 'اشتراك مياه جديد';
    $alias2->is_active = true;

    $this->aliasRepo->shouldReceive('all')
        ->once()
        ->andReturn(new Collection([$alias1, $alias2]));

    $this->serviceQuery->shouldReceive('findPublishedByExactName')
        ->with($this->normalizer->normalize('خدمة مياه'))
        ->andReturn($service1);

    $this->serviceQuery->shouldReceive('findPublishedByExactName')
        ->with($this->normalizer->normalize('اشتراك مياه جديد'))
        ->andReturn($service2);

    // Exact match on 'مياه' should win
    $result = $this->resolver->resolve($this->normalizer->normalize('مياه'));

    expect($result)->not->toBeNull();
    expect($result->id)->toBe(1);
});

it('uses priority for tie-breaking', function (): void {
    $service1 = new ResolvedServiceData(id: 1, name: 'خدمة أ');
    $service2 = new ResolvedServiceData(id: 2, name: 'خدمة ب');

    $alias1 = new ChatbotServiceAlias;
    $alias1->alias = 'مياه';
    $alias1->service_key = 'خدمة أ';
    $alias1->is_active = true;
    $alias1->metadata = ['priority' => 10];

    $alias2 = new ChatbotServiceAlias;
    $alias2->alias = 'مياه';
    $alias2->service_key = 'خدمة ب';
    $alias2->is_active = true;
    $alias2->metadata = ['priority' => 20];

    $this->aliasRepo->shouldReceive('all')
        ->once()
        ->andReturn(new Collection([$alias1, $alias2]));

    $this->serviceQuery->shouldReceive('findPublishedByExactName')
        ->with($this->normalizer->normalize('خدمة أ'))
        ->andReturn($service1);

    $this->serviceQuery->shouldReceive('findPublishedByExactName')
        ->with($this->normalizer->normalize('خدمة ب'))
        ->andReturn($service2);

    // Both match exactly, higher priority should win
    $result = $this->resolver->resolve($this->normalizer->normalize('مياه'));

    expect($result)->not->toBeNull();
    expect($result->id)->toBe(2);
});

it('returns multiple candidates for clarification', function (): void {
    $service1 = new ResolvedServiceData(id: 1, name: 'اشتراك مياه جديد');
    $service2 = new ResolvedServiceData(id: 2, name: 'نقل اشتراك مياه');
    $service3 = new ResolvedServiceData(id: 3, name: 'فصل اشتراك مياه');

    $alias1 = new ChatbotServiceAlias;
    $alias1->alias = 'مياه';
    $alias1->service_key = 'اشتراك مياه جديد';
    $alias1->is_active = true;

    $alias2 = new ChatbotServiceAlias;
    $alias2->alias = 'مياه';
    $alias2->service_key = 'نقل اشتراك مياه';
    $alias2->is_active = true;

    $alias3 = new ChatbotServiceAlias;
    $alias3->alias = 'مياه';
    $alias3->service_key = 'فصل اشتراك مياه';
    $alias3->is_active = true;

    $this->aliasRepo->shouldReceive('all')
        ->once()
        ->andReturn(new Collection([$alias1, $alias2, $alias3]));

    $this->serviceQuery->shouldReceive('findPublishedByExactName')
        ->with($this->normalizer->normalize('اشتراك مياه جديد'))
        ->andReturn($service1);

    $this->serviceQuery->shouldReceive('findPublishedByExactName')
        ->with($this->normalizer->normalize('نقل اشتراك مياه'))
        ->andReturn($service2);

    $this->serviceQuery->shouldReceive('findPublishedByExactName')
        ->with($this->normalizer->normalize('فصل اشتراك مياه'))
        ->andReturn($service3);

    $candidates = $this->resolver->resolveMultiple($this->normalizer->normalize('مياه'));

    expect($candidates)->toHaveCount(3);
});

it('ignores aliases shorter than 3 characters', function (): void {
    $alias = new ChatbotServiceAlias;
    $alias->alias = 'مي'; // Only 2 chars
    $alias->service_key = 'خدمة قصيرة';
    $alias->is_active = true;

    $this->aliasRepo->shouldReceive('all')
        ->once()
        ->andReturn(new Collection([$alias]));

    $result = $this->resolver->resolve('مياه خدمة');

    expect($result)->toBeNull();
});

it('falls back to context service name when no direct match', function (): void {
    $service = new ResolvedServiceData(id: 5, name: 'رخصة بناء');

    $this->serviceQuery->shouldReceive('findPublishedByExactName')
        ->with('رخصة بناء')
        ->andReturn($service);

    $result = $this->resolver->resolve('كم الرسوم', 'رخصة بناء');

    expect($result)->not->toBeNull();
    expect($result->id)->toBe(5);
});

it('does not match unpublished services', function (): void {
    $result = $this->resolver->resolve('خدمة غير موجودة');

    expect($result)->toBeNull();
});
