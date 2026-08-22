<?php

declare(strict_types=1);

namespace App\Domains\Chatbot\Services;

final readonly class ResponseTextPresenter
{
    public function normalizeDisplayText(string $text): string
    {
        $text = $this->decodeEntitiesOnce($text);

        $text = str_replace(['\\r\\n', '\\r', '\\n'], "\n", $text);

        $text = preg_replace('/\r\n|\r|\n/u', "\n", $text);

        $text = preg_replace('/^[ \t]+|[ \t]+$/mu', '', $text);

        $text = preg_replace('/\n{3,}/u', "\n\n", $text);

        return trim($text);
    }

    public function normalizeText(string $text): string
    {
        return $this->normalizeDisplayText($text);
    }

    public function decodeEntitiesOnce(string $text): string
    {
        return html_entity_decode($text, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    }

    public function presentActions(array $actions): array
    {
        return array_values(array_filter(array_map(function (array $action): array {
            return array_map(fn ($value) => is_string($value)
                ? $this->normalizeDisplayText($value)
                : $value, $action);
        }, $actions), fn (array $action) => isset($action['label']) && ($action['label'] !== '')));
    }
}
