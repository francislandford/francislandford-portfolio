<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Not User::factory(): factories require fakerphp/faker, a dev-only
        // dependency that isn't installed in production (composer install --no-dev).
        $owner = User::updateOrCreate(
            ['email' => 'contact@francislandford.com'],
            [
                'name' => 'Francis Landford',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'is_admin' => true,
            ]
        );

        $this->call([
            RoleSeeder::class,
            SettingsSeeder::class,
            ServiceSeeder::class,
            ProjectSeeder::class,
            SocialLinkSeeder::class,
            SkillSeeder::class,
            ExperienceSeeder::class,
            EducationSeeder::class,
            CertificationSeeder::class,
            BlogPostSeeder::class,
            LaravelFundamentalsCourseSeeder::class,
        ]);

        $owner->assignRole('super_admin');
    }
}
