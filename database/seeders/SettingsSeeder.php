<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            'name' => 'Francis Landford',
            'tagline' => 'Software Engineer | Creative Problem Solver | Innovative Software Solutions & Modern Applications',
            'hero_headline' => 'Building Smart Software Solutions',
            'hero_subheadline' => 'I create and develop tailored digital solutions that help individuals and organizations achieve their goals through meaningful user experiences.',
            'site_meta_description' => 'Professional software engineer specializing in innovative solutions, scalable systems, and modern applications. Showcasing creative projects and cutting-edge technology.',
            'phone' => '+231 777 810 466',
            'email' => 'contact@francislandford.com',
            'address' => 'Soul Clinic, New Paynesville, Monrovia, Liberia',
            'website' => 'https://francislandford.com',
            'stat_years_experience' => '5+',
            'stat_projects_completed' => '10+',
            'stat_happy_clients' => '10+',
            'stat_awards' => '5',
            // Placeholder — not Francis's real handle. Replace before the GitHub
            // digest is regenerated, or it will summarize the wrong account.
            'github_username' => 'octocat',
        ];

        foreach ($settings as $key => $value) {
            Setting::set($key, $value);
        }
    }
}
