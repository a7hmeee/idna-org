<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Domains\Homepage\Models\HomepageSetting;
use Illuminate\Database\Eloquent\Factories\Factory;

final class HomepageSettingFactory extends Factory
{
    protected $model = HomepageSetting::class;

    public function definition(): array
    {
        return [
            'site_title' => 'بلدية إذنا',
            'site_subtitle' => 'Municipality of Idna',
            'portal_url' => 'https://i.palexpand.ps/portal',
            'primary_button_text' => 'الدخول إلى البوابة',
            'secondary_button_text' => 'تعرف على البلدية',
            'welcome_title' => 'مرحباً بكم في بلدية إذنا',
            'show_mayor_message' => true,
        ];
    }
}
