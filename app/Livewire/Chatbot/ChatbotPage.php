<?php

declare(strict_types=1);

namespace App\Livewire\Chatbot;

use Illuminate\View\View;
use Livewire\Component;

final class ChatbotPage extends Component
{
    use BaseChatbot;

    public function render(): View
    {
        return view('livewire.chatbot.chatbot-page')
            ->layout('layouts.home', [
                'title' => 'المساعد الذكي | بلدية إذنا',
            ]);
    }
}
