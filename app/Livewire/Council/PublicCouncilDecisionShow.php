<?php

declare(strict_types=1);

namespace App\Livewire\Council;

use App\Domains\Municipality\Contracts\CouncilDecisionRepositoryInterface;
use App\Domains\Municipality\Enums\CouncilDecisionStatus;
use App\Domains\Municipality\Enums\CouncilDecisionType;
use App\Domains\Municipality\Models\Municipality;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;

final class PublicCouncilDecisionShow extends Component
{
    public $decision;
    public ?array $relatedDecisions = [];
    public ?array $previousDecision = null;
    public ?array $nextDecision = null;

    public function mount($decision): void
    {
        $repo = app(CouncilDecisionRepositoryInterface::class);

        $model = $repo->findPublicById((int) $decision);

        if (!$model) {
            abort(404);
        }

        $this->decision = $model;

        $related = $repo->getRelatedPublishedDecisions(
            $model->id,
            $model->type,
            3
        );

        $this->relatedDecisions = $related;

        $prev = $repo->getPreviousDecision($model->id, $model->decision_date);
        $next = $repo->getNextDecision($model->id, $model->decision_date);

        $this->previousDecision = $prev ? [
            'id' => $prev->id,
            'decision_number' => $prev->decision_number,
            'title' => $prev->title,
        ] : null;

        $this->nextDecision = $next ? [
            'id' => $next->id,
            'decision_number' => $next->decision_number,
            'title' => $next->title,
        ] : null;
    }

    public function render()
    {
        $municipalityName = 'بلدية إذنا';
        $municipality = Municipality::first();
        if ($municipality) {
            $municipalityName = $municipality->name_ar ?? $municipalityName;
        }

        $typeLabel = CouncilDecisionType::tryFrom($this->decision->type)?->label() ?? $this->decision->type;
        $statusLabel = CouncilDecisionStatus::tryFrom($this->decision->status)?->label() ?? $this->decision->status;

        $attachmentExists = false;
        $attachmentName = null;
        $attachmentUrl = null;
        if ($this->decision->attachment_path) {
            $attachmentUrl = asset('storage/' . $this->decision->attachment_path);
            $attachmentExists = Storage::disk('public')->exists($this->decision->attachment_path);
            $attachmentName = basename($this->decision->attachment_path);
        }

        $ogTitle = $this->decision->title . ' | ' . $municipalityName;
        $ogDescription = $this->decision->summary ?? 'قرار رقم ' . $this->decision->decision_number;

        return view('livewire.council.public-council-decision-show', [
            'municipalityName' => $municipalityName,
            'typeLabel' => $typeLabel,
            'statusLabel' => $statusLabel,
            'attachmentExists' => $attachmentExists,
            'attachmentName' => $attachmentName,
            'attachmentUrl' => $attachmentUrl,
            'ogTitle' => $ogTitle,
            'ogDescription' => $ogDescription,
        ])->layout('layouts.home', [
            'title' => $ogTitle,
            'metaDescription' => $ogDescription,
        ]);
    }
}
