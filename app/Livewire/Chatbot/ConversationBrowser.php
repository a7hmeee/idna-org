<?php

declare(strict_types=1);

namespace App\Livewire\Chatbot;

use App\Domains\Chatbot\Models\ChatbotConversation;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.dashboard')]
final class ConversationBrowser extends Component
{
    use WithPagination;

    public string $search = '';

    public string $status = '';

    public string $dateFrom = '';

    public string $dateTo = '';

    public bool $showMessagesModal = false;

    public ?ChatbotConversation $selectedConversation = null;

    public function boot(): void
    {
        if (! auth()->user()->can('chatbot.conversations.view')) {
            abort(403);
        }
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedStatus(): void
    {
        $this->resetPage();
    }

    public function updatedDateFrom(): void
    {
        $this->resetPage();
    }

    public function updatedDateTo(): void
    {
        $this->resetPage();
    }

    public function getConversations(): LengthAwarePaginator
    {
        $query = ChatbotConversation::query()->with('user')->latest();

        if ($this->search) {
            $search = $this->search;
            $query->where(function ($q) use ($search) {
                $q->where('session_id', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($uq) use ($search) {
                        $uq->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
            });
        }

        if ($this->status) {
            $query->where('status', $this->status);
        }

        if ($this->dateFrom && $this->dateTo) {
            $query->whereBetween('created_at', [$this->dateFrom, $this->dateTo.' 23:59:59']);
        } elseif ($this->dateFrom) {
            $query->where('created_at', '>=', $this->dateFrom);
        } elseif ($this->dateTo) {
            $query->where('created_at', '<=', $this->dateTo.' 23:59:59');
        }

        return $query->paginate(15);
    }

    public function viewMessages(int $id): void
    {
        $conversation = ChatbotConversation::with(['messages', 'user'])->find($id);

        if ($conversation) {
            $this->selectedConversation = $conversation;
            $this->showMessagesModal = true;
        }
    }

    public function closeMessagesModal(): void
    {
        $this->showMessagesModal = false;
        $this->selectedConversation = null;
    }

    public function render()
    {
        return view('livewire.chatbot.conversation-browser', [
            'conversations' => $this->getConversations(),
        ]);
    }
}
