<?php

declare(strict_types=1);

namespace App\Livewire\PageCarousels;

use App\Domains\Homepage\Models\CarouselConfiguration;
use App\Domains\Homepage\Models\HomepageSetting;
use App\Domains\Homepage\Services\CarouselRegistry;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.dashboard')]
final class CarouselConfigManager extends Component
{
    public ?int $editingId = null;

    public string $editName = '';

    public ?string $editTitle = null;

    public ?string $editSubtitle = null;

    public bool $editIsActive = true;

    public int $editDesktopSlides = 1;

    public int $editTabletSlides = 1;

    public int $editMobileSlides = 1;

    public bool $editAutoplay = true;

    public int $editAutoplayDelay = 8000;

    public bool $editLoop = false;

    public bool $editShowNavigation = true;

    public bool $editShowPagination = true;

    public bool $editPauseOnHover = true;

    public string $editDirection = 'rtl';

    public string $editTransition = 'slide';

    public int $editSortOrder = 0;

    public bool $showEditModal = false;

    public string $search = '';

    public string $filterPage = '';

    public function mount(): void
    {
        $this->authorize('viewSlides', HomepageSetting::class);

        // Auto-sync on first load
        CarouselRegistry::sync();
    }

    public function sync(): void
    {
        $added = CarouselRegistry::sync();
        session()->flash('success', $added > 0
            ? "تم إضافة {$added} كاروسيل جديد."
            : 'جميع الكاروسيلات مسجلة بالفعل.');
    }

    public function edit(int $id): void
    {
        $this->authorize('updateSlide', HomepageSetting::class);

        $config = CarouselConfiguration::findOrFail($id);
        $this->editingId = $config->id;
        $this->editName = $config->name;
        $this->editTitle = $config->title;
        $this->editSubtitle = $config->subtitle;
        $this->editIsActive = $config->is_active;
        $this->editDesktopSlides = $config->desktop_slides;
        $this->editTabletSlides = $config->tablet_slides;
        $this->editMobileSlides = $config->mobile_slides;
        $this->editAutoplay = $config->autoplay;
        $this->editAutoplayDelay = $config->autoplay_delay;
        $this->editLoop = $config->loop;
        $this->editShowNavigation = $config->show_navigation;
        $this->editShowPagination = $config->show_pagination;
        $this->editPauseOnHover = $config->pause_on_hover;
        $this->editDirection = $config->direction;
        $this->editTransition = $config->transition;
        $this->editSortOrder = $config->sort_order;
        $this->showEditModal = true;
    }

    public function save(): void
    {
        $this->authorize('updateSlide', HomepageSetting::class);

        $validated = $this->validate([
            'editName' => ['required', 'string', 'max:255'],
            'editTitle' => ['nullable', 'string', 'max:255'],
            'editSubtitle' => ['nullable', 'string', 'max:500'],
            'editDesktopSlides' => ['required', 'integer', 'min:1', 'max:12'],
            'editTabletSlides' => ['required', 'integer', 'min:1', 'max:8'],
            'editMobileSlides' => ['required', 'integer', 'min:1', 'max:4'],
            'editAutoplayDelay' => ['required', 'integer', 'min:1000', 'max:30000'],
            'editSortOrder' => ['required', 'integer', 'min:0'],
        ]);

        $config = CarouselConfiguration::findOrFail($this->editingId);
        $config->update([
            'name' => $validated['editName'],
            'title' => $validated['editTitle'] ?: null,
            'subtitle' => $validated['editSubtitle'] ?: null,
            'is_active' => $this->editIsActive,
            'desktop_slides' => $validated['editDesktopSlides'],
            'tablet_slides' => $validated['editTabletSlides'],
            'mobile_slides' => $validated['editMobileSlides'],
            'autoplay' => $this->editAutoplay,
            'autoplay_delay' => $validated['editAutoplayDelay'],
            'loop' => $this->editLoop,
            'show_navigation' => $this->editShowNavigation,
            'show_pagination' => $this->editShowPagination,
            'pause_on_hover' => $this->editPauseOnHover,
            'direction' => $this->editDirection,
            'transition' => $this->editTransition,
            'sort_order' => $validated['editSortOrder'],
        ]);

        CarouselRegistry::clearCache();

        $this->showEditModal = false;
        $this->editingId = null;
        session()->flash('success', 'تم حفظ إعدادات الكاروسيل بنجاح.');
    }

    public function toggle(int $id): void
    {
        $this->authorize('updateSlide', HomepageSetting::class);

        $config = CarouselConfiguration::findOrFail($id);
        $config->update(['is_active' => ! $config->is_active]);

        CarouselRegistry::clearCache();

        session()->flash('success', 'تم تغيير حالة الكاروسيل.');
    }

    public function closeEditModal(): void
    {
        $this->showEditModal = false;
        $this->editingId = null;
    }

    public function render()
    {
        $query = CarouselConfiguration::query();

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('name', 'like', "%{$this->search}%")
                    ->orWhere('key', 'like', "%{$this->search}%")
                    ->orWhere('page', 'like', "%{$this->search}%")
                    ->orWhere('title', 'like', "%{$this->search}%");
            });
        }

        if ($this->filterPage) {
            $query->where('page', $this->filterPage);
        }

        $carousels = $query->orderBy('sort_order')->orderBy('name')->get();

        $pages = CarouselConfiguration::distinct()->whereNotNull('page')->pluck('page');

        return view('livewire.page-carousels.carousel-config-manager', [
            'carousels' => $carousels,
            'pages' => $pages,
        ]);
    }
}
