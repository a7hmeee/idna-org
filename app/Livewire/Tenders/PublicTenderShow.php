<?php

declare(strict_types=1);

namespace App\Livewire\Tenders;

use App\Domains\Municipality\Models\Municipality;
use App\Domains\Tenders\Actions\RecordTenderViewAction;
use App\Domains\Tenders\Contracts\TenderRepositoryInterface;
use App\Domains\Tenders\Models\Tender;
use Livewire\Component;

final class PublicTenderShow extends Component
{
    public ?Tender $tender = null;

    public function mount(?Tender $tender = null): void
    {
        if ($tender && $tender->exists) {
            abort_if(! $tender->is_public || $tender->status->value !== 'open', 404);

            $this->tender = $tender;

            app(RecordTenderViewAction::class)->execute($tender->id);
        }

        abort_unless($this->tender, 404);
    }

    public function render()
    {
        $municipalityName = 'بلدية إذنا';
        try {
            $municipality = Municipality::first();
            if ($municipality) {
                $municipalityName = $municipality->name_ar ?? $municipalityName;
            }
        } catch (\Throwable $e) {
        }

        $relatedTenders = app(TenderRepositoryInterface::class)->getLatest(3)
            ->reject(fn ($t) => $t->id === $this->tender->id)
            ->take(3);

        return view('livewire.tenders.public-tender-show', [
            'municipalityName' => $municipalityName,
            'relatedTenders' => $relatedTenders,
        ])->layout('layouts.home', [
            'title' => ($this->tender->title_ar ?? 'المناقصة').' | '.$municipalityName,
            'metaDescription' => $this->tender->summary ?? 'التفاصيل الكاملة للمناقصة '.($this->tender->title_ar ?? '').' في '.$municipalityName,
        ]);
    }
}
