<?php

use App\Domains\Chatbot\Providers\ChatbotServiceProvider;
use App\Domains\CitizenWorkflows\Providers\CitizenWorkflowServiceProvider;
use App\Domains\Dashboard\Providers\DashboardServiceProvider;
use App\Providers\AppServiceProvider;

return [
    AppServiceProvider::class,
    DashboardServiceProvider::class,
    CitizenWorkflowServiceProvider::class,
    ChatbotServiceProvider::class,
];
