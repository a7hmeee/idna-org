<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Domains\Chatbot\Contracts\ConversationContextInterface;
use App\Domains\Chatbot\Contracts\HybridIntentPredictorInterface;
use App\Domains\Chatbot\Contracts\MunicipalityDomainRouterInterface;
use App\Domains\Chatbot\DTOs\IncomingChatMessageData;
use App\Domains\Chatbot\Services\ArabicTextNormalizer;
use App\Domains\Chatbot\Services\ChatResponseHandlerRegistry;
use Illuminate\Console\Command;

final class ChatbotDomainTestCommand extends Command
{
    protected $signature = 'chatbot:domain-test
        {message : The citizen message to test}
        {--show-route : Show domain routing details}
        {--show-context : Show conversation context}
        {--conversation= : Session ID for context persistence}
        {--json : Output as JSON}';

    protected $description = 'Test chatbot domain routing and response';

    public function handle(
        ArabicTextNormalizer $normalizer,
        HybridIntentPredictorInterface $predictor,
        MunicipalityDomainRouterInterface $router,
        ConversationContextInterface $context,
        ChatResponseHandlerRegistry $registry,
    ): int {
        $startTime = microtime(true);

        $message = $this->argument('message');
        $sessionId = $this->option('conversation') ?? 'cli-test-'.md5($message.microtime());
        $asJson = (bool) $this->option('json');

        $normalized = $normalizer->normalize($message);
        $prediction = $predictor->predict($normalized);
        $state = $context->getState($sessionId);
        $route = $router->route($prediction->intent, $normalized, $state);

        $handler = $registry->resolve($prediction->intent);
        $incoming = new IncomingChatMessageData(
            message: $message,
            sessionId: $sessionId,
        );
        $response = $handler->handle($incoming, null);

        $executionTimeMs = (int) round((microtime(true) - $startTime) * 1000);

        if ($asJson) {
            $this->line(json_encode([
                'message' => $message,
                'normalized' => $normalized,
                'intent' => $prediction->intent->value,
                'intent_label' => $prediction->intent->label(),
                'confidence' => $prediction->confidence,
                'source' => $prediction->source,
                'domain' => $route->domain,
                'handler' => $route->handlerKey,
                'requires_entity' => $route->requiresEntity,
                'entity_type' => $route->requiredEntityType,
                'response_type' => $response->type,
                'response_message' => $response->message,
                'needs_clarification' => $response->needsClarification,
                'execution_time_ms' => $executionTimeMs,
            ], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));

            return self::SUCCESS;
        }

        $this->components->twoColumnDetail('Message', $message);
        $this->components->twoColumnDetail('Normalized', $normalized);
        $this->components->twoColumnDetail('Predicted Intent', "{$prediction->intent->label()} ({$prediction->intent->value})");
        $this->components->twoColumnDetail('Confidence', (string) $prediction->confidence);
        $this->components->twoColumnDetail('Source', $prediction->source);

        if ($this->option('show-route')) {
            $this->components->twoColumnDetail('Routed Domain', $route->domain);
            $this->components->twoColumnDetail('Handler', $route->handlerKey);
            $this->components->twoColumnDetail('Requires Entity', $route->requiresEntity ? "{$route->requiredEntityType}" : 'No');
        }

        $this->components->twoColumnDetail('Response Type', $response->type);
        $this->components->twoColumnDetail('Needs Clarification', $response->needsClarification ? 'Yes' : 'No');
        $this->components->twoColumnDetail('Execution Time', "{$executionTimeMs}ms");

        $this->newLine();
        $this->components->alert($response->message);

        return self::SUCCESS;
    }
}
