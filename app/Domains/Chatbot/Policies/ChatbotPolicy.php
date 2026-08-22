<?php

declare(strict_types=1);

namespace App\Domains\Chatbot\Policies;

use App\Domains\Authentication\Models\User;

final class ChatbotPolicy
{
    public function view(User $user): bool
    {
        return $user->can('chatbot.view');
    }

    public function manage(User $user): bool
    {
        return $user->can('chatbot.manage');
    }

    public function viewConversations(User $user): bool
    {
        return $user->can('chatbot.conversations.view');
    }

    public function viewFeedback(User $user): bool
    {
        return $user->can('chatbot.feedback.view');
    }

    public function viewModels(User $user): bool
    {
        return $user->can('chatbot.models.view');
    }

    public function viewAliases(User $user): bool
    {
        return $user->can('chatbot.aliases.view');
    }
}
