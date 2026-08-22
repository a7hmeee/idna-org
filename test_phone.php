<?php

use App\Domains\Chatbot\Services\PublicChatbotDataQualityGuard;

require 'vendor/autoload.php';
$g = new PublicChatbotDataQualityGuard;
$v = '+970-22-123456';
$n = preg_replace('/[^0-9+]/', '', $v);
var_dump($n);
var_dump(preg_match('/^\+?970-22-123456$/', $n));
