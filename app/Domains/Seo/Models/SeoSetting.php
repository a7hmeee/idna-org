<?php

declare(strict_types=1);

namespace App\Domains\Seo\Models;

use Illuminate\Database\Eloquent\Model;

final class SeoSetting extends Model
{
    protected $fillable = [
        'page_key',
        'title',
        'meta_description',
        'meta_keywords',
        'og_title',
        'og_description',
        'og_image',
        'twitter_title',
        'twitter_description',
        'twitter_image',
        'canonical_url',
        'robots',
        'author',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeForPage($query, string $pageKey)
    {
        return $query->where('page_key', $pageKey);
    }

    public static function getForPage(string $pageKey): ?self
    {
        return self::where('page_key', $pageKey)->where('is_active', true)->first();
    }

    public static function getGlobal(): ?self
    {
        return self::where('page_key', 'global')->where('is_active', true)->first();
    }

    public static function getForPageWithFallback(string $pageKey): self
    {
        $pageSeo = self::getForPage($pageKey);
        if ($pageSeo) {
            return $pageSeo;
        }

        $globalSeo = self::getGlobal();
        if ($globalSeo) {
            return $globalSeo;
        }

        return new self([
            'title' => config('app.name'),
            'meta_description' => '',
            'robots' => 'index,follow',
        ]);
    }
}
