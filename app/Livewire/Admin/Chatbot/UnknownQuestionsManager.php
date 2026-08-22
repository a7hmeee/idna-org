<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Chatbot;

use App\Domains\ChatbotAnalytics\Contracts\UnknownQuestionRepositoryInterface;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.dashboard')]
final class UnknownQuestionsManager extends Component
{
    use WithPagination;

    public string $statusFilter = 'new';

    public ?int $updatingId = null;

    public string $newStatus = 'reviewed';

    public string $adminNotes = '';

    public function updatedStatusFilter(): void
    {
        $this->resetPage();
    }

    public function openUpdate(int $id): void
    {
        $this->updatingId = $id;
        $this->adminNotes = '';
        $this->newStatus = 'reviewed';
    }

    public function cancelUpdate(): void
    {
        $this->updatingId = null;
    }

    public function updateStatus(UnknownQuestionRepositoryInterface $repository): void
    {
        if ($this->updatingId === null) {
            return;
        }

        $this->validate([
            'newStatus' => 'required|in:reviewed,resolved,ignored',
            'adminNotes' => 'nullable|string|max:1000',
        ]);

        $repository->updateStatus(
            id: $this->updatingId,
            status: $this->newStatus,
            notes: $this->adminNotes ?: null,
        );

        $this->updatingId = null;
        $this->dispatch('status-updated');
    }

    public function render(UnknownQuestionRepositoryInterface $repository): View
    {
        $questions = $repository->getAll($this->statusFilter, 20);

        return view('livewire.admin.chatbot.unknown-questions-manager', [
            'questions' => $questions,
            'counts' => [
                'new' => $repository->getTotalCount('new'),
                'reviewed' => $repository->getTotalCount('reviewed'),
                'resolved' => $repository->getTotalCount('resolved'),
                'ignored' => $repository->getTotalCount('ignored'),
            ],
        ]);
    }
}
