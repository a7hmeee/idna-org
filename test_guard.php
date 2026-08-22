<?php

use App\Domains\Chatbot\Services\PublicChatbotDataQualityGuard;

require 'vendor/autoload.php';
$g = new PublicChatbotDataQualityGuard;
var_dump($g->isLoremOrFaker('Lorem ipsum dolor sit amet'));
var_dump($g->isDemoPhone('+970-22-123456'));
var_dump($g->isPlaceholderEmail('info@idhna.ps'));
