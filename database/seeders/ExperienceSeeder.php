<?php

namespace Database\Seeders;

use App\Models\Experience;
use Illuminate\Database\Seeder;

class ExperienceSeeder extends Seeder
{
    /**
     * Provided directly by the site owner (exact months, not just years —
     * supersedes the earlier francislandford.com-derived approximation).
     */
    public function run(): void
    {
        $roles = [
            [
                'role_title' => 'Software Engineer',
                'company' => 'Vibnix',
                'start_date' => '2022-08-01',
                'end_date' => null,
                'description' => "Built and maintained scalable applications.\nCollaborated with teams to deliver client-focused solutions.",
                'order' => 1,
            ],
            [
                'role_title' => 'Developer / Digital Products Analyst',
                'company' => 'FrontPage Africa',
                'start_date' => '2022-09-01',
                'end_date' => null,
                'description' => "Optimized publishing workflows and digital product performance.\nSupported product launches and enhanced user engagement.\nAuthored articles on politics, society, and human rights.",
                'order' => 2,
            ],
            [
                'role_title' => 'Lead Software Developer',
                'company' => 'Diggy Solutions',
                'start_date' => '2022-06-01',
                'end_date' => '2025-06-30',
                'description' => "Directed a team in building custom software solutions.\nOversaw project lifecycles from design to deployment.",
                'order' => 3,
            ],
            [
                'role_title' => 'Bank Teller',
                'company' => 'Genuine Financial Services',
                'start_date' => '2022-04-01',
                'end_date' => '2023-07-31',
                'description' => 'Handled financial transactions and customer service.',
                'order' => 4,
            ],
            [
                'role_title' => 'Software Application Developer',
                'company' => 'Macnet Technologies',
                'start_date' => '2021-10-01',
                'end_date' => '2022-11-30',
                'description' => 'Designed and implemented software applications.',
                'order' => 5,
            ],
            [
                'role_title' => 'Information Technology Officer',
                'company' => 'J-MO Global, LLC',
                'start_date' => '2021-02-01',
                'end_date' => '2022-10-31',
                'description' => 'Provided IT support and system maintenance.',
                'order' => 6,
            ],
            [
                'role_title' => 'Office Administrative Assistant',
                'company' => 'Pleebo City Corporation',
                'start_date' => '2016-05-01',
                'end_date' => '2017-01-31',
                'description' => 'Supported administrative operations and documentation.',
                'order' => 7,
            ],
            [
                'role_title' => 'Communicator',
                'company' => 'Mercy Corps',
                'start_date' => '2015-01-01',
                'end_date' => '2016-01-31',
                'description' => 'Assisted in community outreach and program communication.',
                'order' => 8,
            ],
        ];

        // Full replace: the previous seed of this table used less precise,
        // partially different data derived from the old site.
        Experience::query()->delete();

        foreach ($roles as $role) {
            Experience::create($role);
        }
    }
}
