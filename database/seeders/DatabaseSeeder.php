<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $owner = User::factory()->create([
            'name' => 'Francis Landford',
            'email' => 'contact@francislandford.com',
            'is_admin' => true,
        ]);

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
