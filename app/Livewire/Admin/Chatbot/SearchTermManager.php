<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Chatbot;

use App\Domains\Chatbot\Contracts\SmartServiceSearchInterface;
use App\Domains\Chatbot\Services\ArabicTextNormalizer;
use App\Domains\ElectronicServices\Models\ElectronicService;
use App\Domains\ElectronicServices\Models\ServiceSearchTerm;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.dashboard')]
final class SearchTermManager extends Component
{
    use WithPagination;

    public ?int $selectedServiceId = null;

    public string $search = '';

    public string $testQuery = '';

    public ?array $testResult = null;

    // Form fields
    public string $term = '';

    public string $type = 'keyword';

    public int $weight = 10;

    public int $priority = 0;

    public ?int $editingTermId = null;

    protected function rules(): array
    {
        return [
            'term' => 'required|string|min:1|max:255',
            'type' => 'required|in:alias,keyword,phrase,citizen_expression',
            'weight' => 'required|integer|min:1|max:100',
            'priority' => 'required|integer|min:0|max:100',
        ];
    }

    public function render(): View
    {
        $services = ElectronicService::where('is_public', true)
            ->where('status', 'active')
            ->orderBy('name')
            ->get(['id', 'name']);

        $terms = collect();
        if ($this->selectedServiceId) {
            $terms = ServiceSearchTerm::where('electronic_service_id', $this->selectedServiceId)
                ->orderBy('type')
                ->orderByDesc('weight')
                ->get();
        }

        return view('livewire.admin.chatbot.search-term-manager', [
            'services' => $services,
            'terms' => $terms,
        ]);
    }

    public function selectService(int $serviceId): void
    {
        $this->selectedServiceId = $serviceId;
        $this->resetForm();
        $this->testResult = null;
    }

    public function save(): void
    {
        $this->validate();

        $normalizer = app(ArabicTextNormalizer::class);

        $data = [
            'electronic_service_id' => $this->selectedServiceId,
            'term' => $this->term,
            'normalized_term' => $normalizer->normalize($this->term),
            'type' => $this->type,
            'weight' => $this->weight,
            'priority' => $this->priority,
            'is_active' => true,
        ];

        if ($this->editingTermId) {
            ServiceSearchTerm::where('id', $this->editingTermId)->update($data);
            $this->dispatch('notify', message: 'Term updated.', type: 'success');
        } else {
            ServiceSearchTerm::create($data);
            $this->dispatch('notify', message: 'Term added.', type: 'success');
        }

        $this->clearCache();
        $this->resetForm();
    }

    public function edit(int $termId): void
    {
        $term = ServiceSearchTerm::findOrFail($termId);
        $this->editingTermId = $term->id;
        $this->term = $term->term;
        $this->type = $term->type;
        $this->weight = $term->weight;
        $this->priority = $term->priority;
    }

    public function toggleActive(int $termId): void
    {
        $term = ServiceSearchTerm::findOrFail($termId);
        $term->update(['is_active' => ! $term->is_active]);
        $this->clearCache();
        $this->dispatch('notify', message: 'Term status toggled.', type: 'success');
    }

    public function delete(int $termId): void
    {
        ServiceSearchTerm::findOrFail($termId)->delete();
        $this->clearCache();
        $this->dispatch('notify', message: 'Term deleted.', type: 'success');
    }

    public function testSearch(): void
    {
        if (empty($this->testQuery)) {
            $this->testResult = null;

            return;
        }

        $search = app(SmartServiceSearchInterface::class);
        $result = $search->search($this->testQuery, limit: 3);

        $this->testResult = [
            'normalized' => $result->normalizedMessage,
            'best' => $result->bestMatch?->serviceName,
            'best_score' => $result->bestMatch?->score,
            'decision' => match (true) {
                $result->isConfident => 'AUTO_SELECTED',
                $result->requiresClarification => 'CLARIFICATION',
                $result->noMatch => 'NO_MATCH',
                default => 'LOW_CONFIDENCE',
            },
            'candidates' => array_map(fn ($m) => [
                'name' => $m->serviceName,
                'score' => $m->score,
                'matched_by' => $m->matchedBy,
            ], $result->matches),
        ];
    }

    private function resetForm(): void
    {
        $this->term = '';
        $this->type = 'keyword';
        $this->weight = 10;
        $this->priority = 0;
        $this->editingTermId = null;
    }

    private function clearCache(): void
    {
        try {
            app(SmartServiceSearchInterface::class)->clearCache();
        } catch (\Throwable) {
            // Silently fail if cache clear fails
        }
    }
}
