<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Domains\Authentication\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

final class SuperAdminSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::firstOrCreate(
            ['email' => 'admin@idhna.ps'],
            [
                'name' => 'Super Admin',
                'password' => bcrypt('Admin@12345'),
                'email_verified_at' => now(),
                'remember_token' => Str::random(10),
            ],
        );

        $user->update(['password' => bcrypt('Admin@12345')]);
        $user->syncRoles(['Super Admin']);
    }
}
