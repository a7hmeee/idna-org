<?php

declare(strict_types=1);

namespace App\Livewire\Announcements;

use App\Domains\Announcements\Contracts\AnnouncementRepositoryInterface;
use App\Domains\Announcements\Enums\AnnouncementPriority;
use App\Domains\Announcements\Enums\AnnouncementType;
use App\Domains\Homepage\Enums\PageCarouselKey;
use App\Domains\Municipality\Models\Municipality;
use Livewire\Component;
use Livewire\WithPagination;

final class PublicAnnouncementsIndex extends Component
{
    use WithPagination;

    public string $search = '';
    public string $type = '';
    public string $priority = '';
    public string $sort = 'latest';

    protected $queryString = ['search', 'type', 'priority', 'sort'];

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedType(): void
    {
        $this->resetPage();
    }

    public function updatedPriority(): void
    {
        $this->resetPage();
    }

    public function updatedSort(): void
    {
        $this->resetPage();
    }

    public function clearFilters(): void
    {
        $this->search = '';
        $this->type = '';
        $this->priority = '';
        $this->sort = 'latest';
        $this->resetPage();
    }

    public function render()
    {
        $repo = app(AnnouncementRepositoryInterface::class);

        $announcements = $repo->getPublished(
            search: strlen($this->search) >= 2 ? $this->search : null,
            type: $this->type ?: null,
            priority: $this->priority ?: null,
        );
        $featured = $repo->getFeatured();

        $municipalityName = 'بلدية إذنا';
        try {
            $municipality = Municipality::first();
            if ($municipality) {
                $municipalityName = $municipality->name_ar ?? $municipalityName;
            }
        } catch (\Throwable $e) {}

        $types = AnnouncementType::cases();
        $priorities = AnnouncementPriority::cases();

        return view('livewire.announcements.public-announcements-index', [
            'announcements' => $announcements,
            'featured' => $featured,
            'municipalityName' => $municipalityName,
            'types' => $types,
            'priorities' => $priorities,
        ])->layout('layouts.home', [
            'title' => 'الإعلانات | ' . $municipalityName,
            'metaDescription' => 'تصفح جميع الإعلانات الرسمية الصادرة عن ' . $municipalityName . '، واطلع على آخر المستجدات والتنبيهات.',
        ]);
    }
}
