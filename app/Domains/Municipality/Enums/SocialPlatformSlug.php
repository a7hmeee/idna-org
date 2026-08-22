<?php

declare(strict_types=1);

namespace App\Domains\Municipality\Enums;

enum SocialPlatformSlug: string
{
    case Facebook = 'facebook';
    case Instagram = 'instagram';
    case Twitter = 'twitter';
    case YouTube = 'youtube';
    case TikTok = 'tiktok';
    case Telegram = 'telegram';
    case LinkedIn = 'linkedin';
    case WhatsApp = 'whatsapp';
    case Snapchat = 'snapchat';

    public function label(): string
    {
        return match ($this) {
            self::Facebook => 'فيسبوك',
            self::Instagram => 'انستغرام',
            self::Twitter => 'إكس',
            self::YouTube => 'يوتيوب',
            self::TikTok => 'تيك توك',
            self::Telegram => 'تيليغرام',
            self::LinkedIn => 'لينكد إن',
            self::WhatsApp => 'واتساب',
            self::Snapchat => 'سناب شات',
        };
    }

    /**
     * @return array<int, string>
     */
    public static function values(): array
    {
        return array_map(fn (self $case) => $case->value, self::cases());
    }

    /**
     * @return array<string, string>
     */
    public static function options(): array
    {
        $options = [];
        foreach (self::cases() as $case) {
            $options[$case->value] = $case->label();
        }

        return $options;
    }
}
