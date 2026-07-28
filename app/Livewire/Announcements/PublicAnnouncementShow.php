<?php

declare(strict_types=1);

namespace App\Livewire\Announcements;

use App\Domains\Announcements\Actions\RecordAnnouncementViewAction;
use App\Domains\Announcements\Contracts\AnnouncementRepositoryInterface;
use App\Domains\Announcements\Models\Announcement;
use App\Domains\Municipality\Models\Municipality;
use Livewire\Component;

final class PublicAnnouncementShow extends Component
{
    public ?Announcement $announcement = null;

    public function mount(?Announcement $announcement = null): void
    {
        if ($announcement && $announcement->exists) {
            abort_if(!$announcement->isVisible(), 404);

            $this->announcement = $announcement;

            app(RecordAnnouncementViewAction::class)->execute($announcement->id);
        }
    }

    public function render()
    {
        $municipalityName = 'بلدية إذنا';
        try {
            $municipality = Municipality::first();
            if ($municipality) {
                $municipalityName = $municipality->name_ar ?? $municipalityName;
            }
        } catch (\Throwable $e) {}

        $relatedAnnouncements = app(AnnouncementRepositoryInterface::class)->getLatest(3)
            ->reject(fn ($a) => $a->id === $this->announcement->id)
            ->take(3);

        return view('livewire.announcements.public-announcement-show', [
            'municipalityName' => $municipalityName,
            'relatedAnnouncements' => $relatedAnnouncements,
        ])->layout('layouts.home', [
            'title' => ($this->announcement->title ?? 'الإعلان') . ' | ' . $municipalityName,
            'metaDescription' => $this->announcement->short_description ?? 'إعلان من ' . $municipalityName,
        ]);
    }
}
