<?php

declare(strict_types=1);

namespace App\Livewire\Homepage;

use App\Domains\Homepage\Actions\CreateHomepageQuickLinkAction;
use App\Domains\Homepage\Actions\UpdateHomepageQuickLinkAction;
use App\Domains\Homepage\Contracts\HomepageRepositoryInterface;
use App\Domains\Homepage\DTOs\HomepageQuickLinkData;
use App\Domains\Homepage\Enums\HomepageQuickLinkType;
use App\Domains\Homepage\Models\HomepageSetting;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.dashboard')]
final class HomepageQuickLinkForm extends Component
{
    public ?int $linkId = null;
    public string $title = '';
    public ?string $description = null;
    public ?string $icon = null;
    public ?string $url = null;
    public ?string $type = null;
    public bool $isExternal = false;
    public bool $isActive = true;
    public ?int $sortOrder = null;

    public function mount(?int $quickLink = null): void
    {
        if ($quickLink) {
            $this->authorize('updateQuickLink', HomepageSetting::class);

            $link = app(HomepageRepositoryInterface::class)->findQuickLink($quickLink);

            if (!$link) {
                abort(404);
            }

            $this->linkId = $link->id;
            $this->title = $link->title;
            $this->description = $link->description;
            $this->icon = $link->icon;
            $this->url = $link->url;
            $this->type = $link->type;
            $this->isExternal = $link->is_external;
            $this->isActive = $link->is_active;
            $this->sortOrder = $link->sort_order;
        } else {
            $this->authorize('createQuickLink', HomepageSetting::class);
        }
    }

    public function save(): void
    {
        if ($this->linkId) {
            $this->authorize('updateQuickLink', HomepageSetting::class);
        } else {
            $this->authorize('createQuickLink', HomepageSetting::class);
        }

        $validated = $this->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:500'],
            'icon' => ['nullable', 'string', 'max:100'],
            'url' => ['nullable', 'string', 'max:500'],
            'type' => ['nullable', 'string', 'max:50'],
            'isExternal' => ['nullable', 'boolean'],
            'isActive' => ['nullable', 'boolean'],
            'sortOrder' => ['nullable', 'integer', 'min:0'],
        ]);

        if ($this->linkId) {
            $action = app(UpdateHomepageQuickLinkAction::class);
            $action->execute($this->linkId, HomepageQuickLinkData::fromRequest($validated));
            session()->flash('success', 'تم تحديث الرابط السريع بنجاح.');
        } else {
            $action = app(CreateHomepageQuickLinkAction::class);
            $action->execute(HomepageQuickLinkData::fromRequest($validated));
            session()->flash('success', 'تم إنشاء الرابط السريع بنجاح.');
        }

        $this->redirect(route('dashboard.homepage.quick-links'), navigate: true);
    }

    public function render()
    {
        return view('livewire.homepage.quick-link-form', [
            'types' => HomepageQuickLinkType::options(),
        ]);
    }
}
