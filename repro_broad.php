<?php

declare(strict_types=1);

use App\Domains\Chatbot\Services\ArabicTextNormalizer;
use App\Domains\Chatbot\Services\GuidedServiceDiscoveryService;

$svc = app(GuidedServiceDiscoveryService::class);
$normalizer = app(ArabicTextNormalizer::class);

$ref = new ReflectionClass($svc);
$patterns = [];
foreach ($ref->getConstants() as $name => $value) {
    if (is_array($value) && $value !== [] && is_string($value[0]) && str_starts_with($value[0], '/')) {
        $patterns = $value;
        echo 'const '.$name.' count='.count($value)."\n";
        break;
    }
}

if ($patterns === []) {
    echo "no patterns found\n";
    exit;
}

$tests = ['خدمات البلدية', 'الخدمات', 'الخدمات الالكترونية', 'الخدمات الإلكترونية', 'الخدمات البلدية الالكترونية', 'الخدمات العامة', 'خدمة', 'خدمة بلدية', 'ما هي الخدمات', 'الخدمات الالكترونية للبلدية', 'اين الخدمات'];

foreach ($tests as $t) {
    $n = $normalizer->normalize($t);
    $m = null;
    foreach ($patterns as $p) {
        if (preg_match($p, $n)) {
            $m = $p;
            break;
        }
    }
    echo ($m !== null ? 'MATCH ' : 'miss  ').json_encode($t).'  =>  '.json_encode($n)."\n";
}

echo "DONE\n";
