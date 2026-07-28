<?php

declare(strict_types=1);

namespace App\Livewire\Homepage;

use App\Domains\Homepage\Actions\CreateHomepageStatisticAction;
use App\Domains\Homepage\Actions\UpdateHomepageStatisticAction;
use App\Domains\Homepage\Contracts\HomepageRepositoryInterface;
use App\Domains\Homepage\DTOs\HomepageStatisticData;
use App\Domains\Homepage\Models\HomepageSetting;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.dashboard')]
final class HomepageStatisticForm extends Component
{
    public ?int $statisticId = null;
    public string $label = '';
    public string $value = '';
    public ?string $suffix = null;
    public ?string $icon = null;
    public ?string $description = null;
    public bool $isActive = true;
    public ?int $sortOrder = null;

    public function mount(?int $statistic = null): void
    {
        if ($statistic) {
            $this->authorize('updateStatistic', HomepageSetting::class);

            $stat = app(HomepageRepositoryInterface::class)->findStatistic($statistic);

            if (!$stat) {
                abort(404);
            }

            $this->statisticId = $stat->id;
            $this->label = $stat->label;
            $this->value = $stat->value;
            $this->suffix = $stat->suffix;
            $this->icon = $stat->icon;
            $this->description = $stat->description;
            $this->isActive = $stat->is_active;
            $this->sortOrder = $stat->sort_order;
        } else {
            $this->authorize('createStatistic', HomepageSetting::class);
        }
    }

    public function save(): void
    {
        if ($this->statisticId) {
            $this->authorize('updateStatistic', HomepageSetting::class);
        } else {
            $this->authorize('createStatistic', HomepageSetting::class);
        }

        $validated = $this->validate([
            'label' => ['required', 'string', 'max:255'],
            'value' => ['required', 'string', 'max:255'],
            'suffix' => ['nullable', 'string', 'max:100'],
            'icon' => ['nullable', 'string', 'max:100'],
            'description' => ['nullable', 'string', 'max:500'],
            'isActive' => ['nullable', 'boolean'],
            'sortOrder' => ['nullable', 'integer', 'min:0'],
        ]);

        if ($this->statisticId) {
            $action = app(UpdateHomepageStatisticAction::class);
            $action->execute($this->statisticId, HomepageStatisticData::fromRequest($validated));
            session()->flash('success', 'تم تحديث الإحصائية بنجاح.');
        } else {
            $action = app(CreateHomepageStatisticAction::class);
            $action->execute(HomepageStatisticData::fromRequest($validated));
            session()->flash('success', 'تم إنشاء الإحصائية بنجاح.');
        }

        $this->redirect(route('dashboard.homepage.statistics'), navigate: true);
    }

    public function render()
    {
        return view('livewire.homepage.statistic-form');
    }
}
