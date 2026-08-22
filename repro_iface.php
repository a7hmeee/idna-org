<?php

declare(strict_types=1);

use App\Domains\Chatbot\Contracts\ClarificationResolverInterface;

echo 'iface class: '.get_class(app(ClarificationResolverInterface::class))."\n";

$intents = DB::table('chat_intents')->get();
echo 'chat_intents count: '.$intents->count()."\n";
foreach ($intents as $i) {
    echo $i->id.'|'.$i->name.'|'.$i->label_ar."\n";
}
echo "DONE\n";
