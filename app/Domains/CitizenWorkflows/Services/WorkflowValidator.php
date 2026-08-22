<?php

declare(strict_types=1);

namespace App\Domains\CitizenWorkflows\Services;

class WorkflowValidator
{
    private const ALLOWED_CATEGORIES = [
        'خدمات', 'service',
        'بنية تحتية', 'infrastructure',
        'مياه', 'water',
        'كهرباء', 'electricity',
        'طرق', 'roads',
        'صرف صحي', 'sanitation',
        'بيئة', 'environment',
        'ضوضاء', 'noise',
        'إداري', 'اداري', 'administrative',
        'أخرى', 'other',
    ];

    public function validate(string $step, string $input): ?string
    {
        $trimmed = trim($input);

        if ($trimmed === '') {
            return 'القيمة مطلوبة ولا يمكن أن تكون فارغة.';
        }

        if (mb_strlen($trimmed) > 2000) {
            return 'النص طويل جداً.';
        }

        return match ($step) {
            'citizen_name', 'name' => $this->validateName($trimmed),
            'phone' => $this->validatePhone($trimmed),
            'email' => $this->validateEmail($trimmed),
            'category' => $this->validateCategory($trimmed),
            'subject' => $this->validateSubject($trimmed),
            'description', 'message' => $this->validateDescription($trimmed),
            default => null,
        };
    }

    public function normalize(string $step, string $input): string
    {
        $trimmed = trim($input);

        return match ($step) {
            'phone' => preg_replace('/[^\d+]/', '', $trimmed),
            default => $trimmed,
        };
    }

    private function validateName(string $input): ?string
    {
        if (preg_match('/<[^>]*>/', $input)) {
            return 'الاسم لا يمكن أن يحتوي على أكواد HTML.';
        }

        if (is_numeric($input)) {
            return 'الاسم يجب أن يحتوي على حروف وليس أرقام فقط.';
        }

        if (mb_strlen($input) < 2) {
            return 'الاسم يجب أن يتكون من حرفين على الأقل.';
        }

        if (mb_strlen($input) > 100) {
            return 'الاسم طويل جداً (الحد الأقصى 100 حرف).';
        }

        return null;
    }

    private function validatePhone(string $input): ?string
    {
        $digits = preg_replace('/\D/', '', $input);

        if (strlen($digits) < 7) {
            return 'رقم الهاتف غير صحيح. يجب أن يتكون من 7 أرقام على الأقل.';
        }

        if (strlen($digits) > 15) {
            return 'رقم الهاتف غير صحيح. (الحد الأقصى 15 رقم).';
        }

        return null;
    }

    private function validateEmail(string $input): ?string
    {
        if (! filter_var($input, FILTER_VALIDATE_EMAIL)) {
            return 'البريد الإلكتروني غير صحيح.';
        }

        if (mb_strlen($input) > 254) {
            return 'البريد الإلكتروني طويل جداً.';
        }

        return null;
    }

    private function validateCategory(string $input): ?string
    {
        $normalized = trim(mb_strtolower($input));

        $found = false;
        foreach (self::ALLOWED_CATEGORIES as $allowed) {
            if (mb_strtolower($allowed) === $normalized) {
                $found = true;
                break;
            }
        }

        if (! $found) {
            $allowedList = implode('، ', array_unique(array_filter(self::ALLOWED_CATEGORIES, fn ($c) => ! preg_match('/^[a-z]/', $c))));

            return "التصنيف غير صحيح. التصنيفات المتاحة: {$allowedList}";
        }

        return null;
    }

    private function validateSubject(string $input): ?string
    {
        if (preg_match('/<[^>]*>/', $input)) {
            return 'عنوان الشكوى لا يمكن أن يحتوي على أكواد HTML.';
        }

        if (mb_strlen($input) < 3) {
            return 'عنوان الشكوى يجب أن يتكون من 3 أحرف على الأقل.';
        }

        if (mb_strlen($input) > 200) {
            return 'عنوان الشكوى طويل جداً (الحد الأقصى 200 حرف).';
        }

        return null;
    }

    private function validateDescription(string $input): ?string
    {
        if (preg_match('/<[^>]*>/', $input)) {
            return 'الوصف لا يمكن أن يحتوي على أكواد HTML.';
        }

        if (is_array($input) || is_object($input)) {
            return 'الوصف يجب أن يكون نصاً وليس بيانات منظمة.';
        }

        if (mb_strlen($input) < 10) {
            return 'الوصف يجب أن يتكون من 10 أحرف على الأقل.';
        }

        if (mb_strlen($input) > 2000) {
            return 'الوصف طويل جداً (الحد الأقصى 2000 حرف).';
        }

        return null;
    }
}
