<?php

declare(strict_types=1);

namespace App\Domains\CitizenWorkflows\Services;

use App\Domains\Chatbot\Services\ArabicTextNormalizer;

class ConfirmationFlow
{
    private const CONFIRM_WORDS = [
        'نعم', 'نعم صحيح', 'صحيح', 'تأكيد', 'اكد', 'أكد', 'اي', 'إي',
        'موافق', 'تمام اكد', 'نعم تاكيد', 'yes', 'confirm',
        'اكيد', 'أكيد',
    ];

    private const CANCEL_WORDS = [
        'لا', 'اغلاق', 'الغاء', 'يلغي', 'الغي', 'مش صحيح', 'عدل', 'رجوع',
        'تراجع', 'تعديل', 'تغيير', 'no', 'cancel',
        'انهاء', 'إنهاء', 'خلص', 'خلاص', 'ما بد', 'ما بدي', 'ما بدي اكمل',
        'ما بديش', 'ما بديش اكمل', 'كفاية', 'بدي انهي',
    ];

    private const GLOBAL_CANCEL_WORDS = [
        'الغاء', 'يلغي', 'cancel', 'تراجع', 'رجوع',
        'انهاء', 'إنهاء', 'خلاص', 'ما بد', 'ما بدي', 'ما بدي اكمل',
        'ما بديش', 'ما بديش اكمل', 'خلص', 'كفاية', 'بدي انهي',
    ];

    private const GLOBAL_CANCEL_PATTERNS = [
        '/^الغاء\s+(الطلب|الشكوى|طلبي|الخدمة|الخدمه)$/u',
        '/^الغاء\s+طلب$/u',
        '/^انهاء\s+(الطلب|الشكوى|طلبي|الخدمه)$/u',
        '/^(ما|مش)\s+بدي(ش)?\s+اكمل$/u',
        '/^بدي\s+انهي(ش)?$/u',
    ];

    private const HELP_WORDS = [
        'مساعدة', 'help', 'ساعدني', 'اعرف', 'شرح',
    ];

    private const RESTART_WORDS = [
        'ابدأ من جديد', 'ابدأ مرة اخرى', 'جديد', 'new', 'restart',
    ];

    private const HOME_WORDS = [
        'الرئيسية', 'القايمة الرئيسية', 'home', 'menu',
    ];

    private const EXIT_WORDS = [
        'خروج', 'exit',
    ];

    private const CHANGE_TOPIC_WORDS = [
        'تغيير الموضوع', 'موضوع آخر', 'change topic', 'موضوع جديد',
    ];

    private const CONTINUE_WORDS = [
        'متابعة', 'استمر', 'استمر', 'استمرار', 'اسكمال', 'continue', 'نعم استكمل',
    ];

    private const SWITCH_WORDS = [
        'انتقل', 'نعم انتقل', 'بدأ جديد', 'switch', 'بدلا من ثخير', 'بدلا',
        'والانتقال', 'وإلغاء',
    ];

    public function __construct(
        private ArabicTextNormalizer $normalizer = new ArabicTextNormalizer,
    ) {}

    public function isConfirm(string $input): bool
    {
        return $this->matchesAny($this->normalize($input), self::CONFIRM_WORDS);
    }

    public function isCancel(string $input): bool
    {
        return $this->matchesAny($this->normalize($input), self::CANCEL_WORDS);
    }

    public function isGlobalCancel(string $input): bool
    {
        $normalized = $this->normalize($input);

        if ($normalized === '') {
            return false;
        }

        foreach (self::GLOBAL_CANCEL_WORDS as $word) {
            if ($normalized === $this->normalize($word)) {
                return true;
            }
        }

        foreach (self::GLOBAL_CANCEL_PATTERNS as $pattern) {
            if (preg_match($pattern, $normalized)) {
                return true;
            }
        }

        return false;
    }

    public function isHelp(string $input): bool
    {
        $normalized = $this->normalize($input);

        if ($normalized === '') {
            return false;
        }

        foreach (self::HELP_WORDS as $word) {
            if ($normalized === $this->normalize($word)) {
                return true;
            }
        }

        return false;
    }

    public function isRestart(string $input): bool
    {
        $normalized = $this->normalize($input);

        if ($normalized === '') {
            return false;
        }

        foreach (self::RESTART_WORDS as $word) {
            if ($normalized === $this->normalize($word)) {
                return true;
            }
        }

        return false;
    }

    public function isHome(string $input): bool
    {
        $normalized = $this->normalize($input);

        if ($normalized === '') {
            return false;
        }

        foreach (self::HOME_WORDS as $word) {
            if ($normalized === $this->normalize($word)) {
                return true;
            }
        }

        return false;
    }

    public function isExit(string $input): bool
    {
        $normalized = $this->normalize($input);

        if ($normalized === '') {
            return false;
        }

        foreach (self::EXIT_WORDS as $word) {
            if ($normalized === $this->normalize($word)) {
                return true;
            }
        }

        return false;
    }

    public function isChangeTopic(string $input): bool
    {
        $normalized = $this->normalize($input);

        if ($normalized === '') {
            return false;
        }

        foreach (self::CHANGE_TOPIC_WORDS as $word) {
            if ($normalized === $this->normalize($word)) {
                return true;
            }
        }

        return false;
    }

    public function isGlobalCommand(string $input): bool
    {
        return $this->isGlobalCancel($input)
            || $this->isHelp($input)
            || $this->isRestart($input)
            || $this->isHome($input)
            || $this->isExit($input)
            || $this->isChangeTopic($input);
    }

    public function isContinue(string $input): bool
    {
        return $this->matchesAny($this->normalize($input), self::CONTINUE_WORDS);
    }

    public function isSwitch(string $input): bool
    {
        return $this->matchesAny($this->normalize($input), self::SWITCH_WORDS);
    }

    public function getConfirmationActions(): array
    {
        return [
            [
                'key' => 'workflow:confirm',
                'label' => 'نعم، تأكيد',
                'value' => 'تأكيد',
            ],
            [
                'key' => 'workflow:cancel',
                'label' => 'إلغاء',
                'value' => 'إلغاء',
            ],
        ];
    }

    public function getInterruptActions(string $switchLabel, string $workflowLabel): array
    {
        return [
            [
                'key' => 'workflow:continue',
                'label' => "متابعة {$workflowLabel}",
                'value' => 'متابعة',
            ],
            [
                'key' => 'workflow:switch',
                'label' => "إلغاء والانتقال إلى {$switchLabel}",
                'value' => 'إلغاء والانتقال',
            ],
        ];
    }

    /**
     * Token/phrase-aware matching: a single word only matches as a whole
     * token, and a phrase matches as a contiguous token sequence. A word
     * like "اي" therefore never matches inside "الوظايف", and "لا" never
     * matches inside "الالكترونية".
     */
    private function matchesAny(string $normalized, array $words): bool
    {
        if ($normalized === '') {
            return false;
        }

        $tokens = preg_split('/\s+/u', trim($normalized));

        foreach ($words as $word) {
            $normalizedWord = $this->normalize($word);

            if ($normalizedWord === '') {
                continue;
            }

            $wordTokens = preg_split('/\s+/u', $normalizedWord);
            $wordCount = count($wordTokens);

            if ($wordCount === 1) {
                if (in_array($normalizedWord, $tokens, true)) {
                    return true;
                }

                continue;
            }

            $limit = count($tokens) - $wordCount;

            for ($i = 0; $i <= $limit; $i++) {
                if (array_slice($tokens, $i, $wordCount) === $wordTokens) {
                    return true;
                }
            }
        }

        return false;
    }

    private function normalize(string $input): string
    {
        return $this->normalizer->normalize($input);
    }
}
