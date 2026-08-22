<?php

declare(strict_types=1);

use App\Domains\Chatbot\Actions\ProcessRuleBasedChatMessageAction;
use App\Domains\Chatbot\DTOs\IncomingChatMessageData;

$action = app(ProcessRuleBasedChatMessageAction::class);

function send(ProcessRuleBasedChatMessageAction $action, string $session, string $message): void
{
    echo "\n>>> USER: {$message}\n";
    $incoming = new IncomingChatMessageData(message: $message, sessionId: $session);
    try {
        $response = $action->execute($incoming);
        echo "BOT: {$response->message}\n";
        foreach (array_slice($response->items ?? [], 0, 3) as $item) {
            echo '  item: '.json_encode($item, JSON_UNESCAPED_UNICODE)."\n";
        }
        foreach (array_slice($response->actions ?? [], 0, 3) as $a) {
            echo '  action: '.json_encode($a, JSON_UNESCAPED_UNICODE)."\n";
        }
    } catch (Throwable $e) {
        echo 'EXCEPTION: '.$e::class.': '.$e->getMessage()."\n";
        echo 'AT: '.$e->getFile().':'.$e->getLine()."\n";
    }
}

$s = 'repro-I-'.bin2hex(random_bytes(4));
send($action, $s, 'الوظائف');
$s = 'repro-J-'.bin2hex(random_bytes(4));
send($action, $s, 'المرافق العامة');
$s = 'repro-K-'.bin2hex(random_bytes(4));
send($action, $s, 'أعضاء المجلس البلدي');
$s = 'repro-L-'.bin2hex(random_bytes(4));
send($action, $s, 'قرارات المجلس');
$s = 'repro-M-'.bin2hex(random_bytes(4));
send($action, $s, 'آخر الأخبار');
$s = 'repro-N-'.bin2hex(random_bytes(4));
send($action, $s, 'جدول توزيع المياه');
$s = 'repro-O-'.bin2hex(random_bytes(4));
send($action, $s, 'مكاتب هندسية');

echo "DONE\n";
