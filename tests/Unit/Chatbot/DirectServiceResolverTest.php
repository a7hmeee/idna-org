<?php

declare(strict_types=1);

use App\Domains\Chatbot\Contracts\ChatbotServiceAliasRepositoryInterface;
use App\Domains\Chatbot\Contracts\MunicipalityServiceQueryInterface;
use App\Domains\Chatbot\DTOs\ResolvedServiceData;
use App\Domains\Chatbot\Models\ChatbotServiceAlias;
use App\Domains\Chatbot\Services\ArabicTextNormalizer;
use App\Domains\Chatbot\Services\DirectServiceResolver;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;

beforeEach(function (): void {
    $this->normalizer = new ArabicTextNormalizer;
    $this->serviceQuery = Mockery::mock(MunicipalityServiceQueryInterface::class);
    $this->aliasRepository = Mockery::mock(ChatbotServiceAliasRepositoryInterface::class);
    $this->resolver = new DirectServiceResolver(
        $this->serviceQuery,
        $this->aliasRepository,
        $this->normalizer,
    );
});

it('resolves by exact official name', function (): void {
    $service = new ResolvedServiceData(id: 1, name: 'إصدار رخصة بناء');

    $this->serviceQuery->shouldReceive('findPublishedByExactName')
        ->once()
        ->with('اصدار رخصة بناء')
        ->andReturn($service);

    $result = $this->resolver->resolve('اصدار رخصة بناء');

    expect($result)->not->toBeNull();
    expect($result->id)->toBe(1);
});

it('resolves by alias exact match', function (): void {
    $aliasService = new ResolvedServiceData(id: 2, name: 'إصدار رخصة بناء');

    $aliasModel = new ChatbotServiceAlias;
    $aliasModel->alias = 'رخصة بناء';
    $aliasModel->service_key = 'إصدار رخصة بناء';
    $aliasModel->is_active = true;

    $this->aliasRepository->shouldReceive('findByAlias')
        ->once()
        ->with('رخصة بناء')
        ->andReturn($aliasModel);

    $this->serviceQuery->shouldReceive('findPublishedByExactName')
        ->once()
        ->ordered()
        ->with('رخصة بناء')
        ->andReturn(null);

    $this->serviceQuery->shouldReceive('findPublishedByExactName')
        ->once()
        ->ordered()
        ->with('اصدار رخصة بناء')
        ->andReturn($aliasService);

    $result = $this->resolver->resolve('رخصة بناء');

    expect($result)->not->toBeNull();
    expect($result->id)->toBe(2);
});

it('resolves by official name containment', function (): void {
    $service = new ResolvedServiceData(id: 3, name: 'إصدار رخصة بناء');

    $this->serviceQuery->shouldReceive('findPublishedByExactName')
        ->once()
        ->ordered()
        ->with('بدي اعرف معلومات عن اصدار رخصة بناء')
        ->andReturn(null);

    $this->aliasRepository->shouldReceive('findByAlias')
        ->once()
        ->ordered()
        ->with('بدي اعرف معلومات عن اصدار رخصة بناء')
        ->andReturn(null);

    $this->serviceQuery->shouldReceive('findPublishedByText')
        ->once()
        ->ordered()
        ->with('بدي اعرف معلومات عن اصدار رخصة بناء')
        ->andReturn($service);

    $result = $this->resolver->resolve('بدي اعرف معلومات عن اصدار رخصة بناء');

    expect($result)->not->toBeNull();
    expect($result->id)->toBe(3);
});

it('protects against short alias containment', function (): void {
    $aliasModel = new ChatbotServiceAlias;
    $aliasModel->alias = 'ab';
    $aliasModel->service_key = 'خدمة';
    $aliasModel->is_active = true;

    $this->serviceQuery->shouldReceive('findPublishedByExactName')
        ->once()
        ->ordered()
        ->with('مرحبا')
        ->andReturn(null);

    $this->aliasRepository->shouldReceive('findByAlias')
        ->once()
        ->ordered()
        ->with('مرحبا')
        ->andReturn(null);

    $this->serviceQuery->shouldReceive('findPublishedByText')
        ->once()
        ->ordered()
        ->with('مرحبا')
        ->andReturn(null);

    $this->aliasRepository->shouldReceive('all')
        ->once()
        ->ordered()
        ->andReturn(new EloquentCollection([$aliasModel]));

    $result = $this->resolver->resolve('مرحبا');

    expect($result)->toBeNull();
});

it('returns null when no match found', function (): void {
    $this->serviceQuery->shouldReceive('findPublishedByExactName')
        ->once()
        ->ordered()
        ->with('completely unrelated')
        ->andReturn(null);

    $this->aliasRepository->shouldReceive('findByAlias')
        ->once()
        ->ordered()
        ->with('completely unrelated')
        ->andReturn(null);

    $this->serviceQuery->shouldReceive('findPublishedByText')
        ->once()
        ->ordered()
        ->with('completely unrelated')
        ->andReturn(null);

    $this->aliasRepository->shouldReceive('all')
        ->once()
        ->ordered()
        ->andReturn(new EloquentCollection([]));

    $result = $this->resolver->resolve('completely unrelated');

    expect($result)->toBeNull();
});
