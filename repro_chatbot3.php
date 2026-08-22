<?php

declare(strict_types=1);

use App\Domains\Chatbot\Actions\ProcessRuleBasedChatMessageAction;
use App\Domains\Chatbot\Contracts\ConversationContextInterface;
use App\Domains\Chatbot\DTOs\IncomingChatMessageData;
use App\Domains\Chatbot\Services\ArabicTextNormalizer;

$action = app(ProcessRuleBasedChatMessageAction::class);
$context = app(ConversationContextInterface::class);
$normalizer = app(ArabicTextNormalizer::class);

function send(ProcessRuleBasedChatMessageAction $action, string $session, string $message): string
{
    echo "\n>>> USER: {$message}\n";
    $incoming = new IncomingChatMessageData(message: $message, sessionId: $session);
    try {
        $response = $action->execute($incoming);
        echo "BOT: {$response->message}\n";

        return $response->message;
    } catch (Throwable $e) {
        echo 'EXCEPTION: '.$e::class.': '.$e->getMessage()."\n";

        return 'EXCEPTION';
    }
}

echo "########## NORMALIZER CHECK ##########\n";
foreach (['الشؤون الإدارية', 'الشؤون الإدارية.', 'الشؤون الادارية', 'الخدمات الإلكترونية', 'الخدمات الالكترونية', 'شؤون ادارية', 'الشوون الادارية'] as $t) {
    echo "{$t} => ".$normalizer->normalize($t)."\n";
}

echo "\n########## SCENARIO D: categories -> typed label with trailing period ##########\n";
$s = 'repro-D-'.bin2hex(random_bytes(4));
send($action, $s, 'خدمات البلدية');
send($action, $s, 'الشؤون الإدارية.');
send($action, $s, 'الشؤون الادارية');

echo "\n########## SCENARIO E: categories -> Arabic-Indic numeric ##########\n";
$s = 'repro-E-'.bin2hex(random_bytes(4));
send($action, $s, 'خدمات البلدية');
send($action, $s, '٢');

echo "\n########## SCENARIO F: failed search -> main menu numeric ##########\n";
$s = 'repro-F-'.bin2hex(random_bytes(4));
send($action, $s, 'الخدمات الإلكترونية');
send($action, $s, '2');
send($action, $s, '6');

echo "\n########## SCENARIO G: greeting -> 6 (water) ##########\n";
$s = 'repro-G-'.bin2hex(random_bytes(4));
send($action, $s, 'مرحبا');
send($action, $s, '6');

echo "\n########## SCENARIO H: electronic-services label directly ##########\n";
$s = 'repro-H-'.bin2hex(random_bytes(4));
send($action, $s, 'الخدمات الالكترونية');
send($action, $s, '1');

echo "DONE\n";
