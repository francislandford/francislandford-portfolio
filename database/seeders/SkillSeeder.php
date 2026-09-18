<?php

namespace Database\Seeders;

use App\Models\Skill;
use Illuminate\Database\Seeder;

class SkillSeeder extends Seeder
{
    public function run(): void
    {
        // PHP, Laravel, React, React Native, Express, MySQL from the original
        // README; Vue.js confirmed present as a primary skill on francislandford.com.
        $skills = ['PHP', 'Laravel', 'React', 'React Native', 'Express', 'MySQL', 'Vue.js'];

        foreach ($skills as $index => $name) {
            Skill::updateOrCreate(['name' => $name], ['order' => $index + 1]);
        }
    }
}
