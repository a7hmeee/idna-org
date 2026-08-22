<?php

declare(strict_types=1);

namespace App\Livewire\Chatbot;

use Illuminate\View\View;
use Livewire\Component;

final class ChatbotWidget extends Component
{
    use BaseChatbot;

    public bool $widgetOpen = false;

    public function toggle(): void
    {
        $this->widgetOpen = ! $this->widgetOpen;
    }

    public function render(): View
    {
        return view('livewire.chatbot.chatbot-widget');
    }
}
