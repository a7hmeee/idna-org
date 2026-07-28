<?php

declare(strict_types=1);

namespace App\Livewire\Homepage;

use App\Domains\Homepage\Actions\ToggleHomepageSectionAction;
use App\Domains\Homepage\Actions\UpdateHomepageSectionAction;
use App\Domains\Homepage\Contracts\HomepageRepositoryInterface;
use App\Domains\Homepage\DTOs\HomepageSectionData;
use App\Domains\Homepage\Models\HomepageSetting;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.dashboard')]
final class HomepageSectionsManager extends Component
{
    public array $sections = [];

    public function mount(): void
    {
        $this->authorize('updateSection', HomepageSetting::class);

        $this->loadSections();
    }

    public function loadSections(): void
    {
        $this->sections = app(HomepageRepositoryInterface::class)
            ->getSections()
            ->map(fn ($section) => [
                'id' => $section->id,
                'key' => $section->key,
                'title' => $section->title,
                'subtitle' => $section->subtitle,
                'is_enabled' => $section->is_enabled,
                'sort_order' => $section->sort_order,
                'items_limit' => $section->items_limit,
            ])
            ->toArray();
    }

    public function toggle(string $key, ToggleHomepageSectionAction $action): void
    {
        $this->authorize('updateSection', HomepageSetting::class);

        $action->execute($key);

        $this->loadSections();

        session()->flash('success', 'تم تغيير حالة القسم بنجاح.');
    }

    public function updateLimit(string $key, int $limit): void
    {
        $this->authorize('updateSection', HomepageSetting::class);

        $action = app(UpdateHomepageSectionAction::class);
        $action->execute($key, HomepageSectionData::fromRequest([
            'key' => $key,
            'itemsLimit' => max(1, min(50, $limit)),
        ]));

        $this->loadSections();

        session()->flash('success', 'تم تحديث الحد الأقصى للعرض.');
    }

    public function render()
    {
        return view('livewire.homepage.sections-manager', [
            'sections' => $this->sections,
        ]);
    }
}
