<?php

declare(strict_types=1);

namespace App\Livewire\News;

use App\Domains\Municipality\Models\Municipality;
use App\Domains\News\Actions\RecordNewsViewAction;
use App\Domains\News\Contracts\NewsRepositoryInterface;
use App\Domains\News\Models\NewsItem;
use Livewire\Component;

final class PublicNewsShow extends Component
{
    public ?NewsItem $news = null;

    public function mount(?NewsItem $news = null): void
    {
        if ($news && $news->exists) {
            abort_if(! $news->is_public || $news->status->value !== 'published', 404);

            $this->news = $news;

            app(RecordNewsViewAction::class)->execute($news->id);
        }

        abort_unless($this->news, 404);
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

        $relatedNews = app(NewsRepositoryInterface::class)->getLatest(4)
            ->reject(fn ($n) => $n->id === $this->news->id)
            ->take(4);

        return view('livewire.news.public-news-show', [
            'municipalityName' => $municipalityName,
            'relatedNews' => $relatedNews,
        ])->layout('layouts.home', [
            'title' => ($this->news->title_ar ?? 'الخبر').' | '.$municipalityName,
            'metaDescription' => $this->news->meta_description ?? $this->news->summary ?? 'خبر من '.$municipalityName,
        ]);
    }
}
