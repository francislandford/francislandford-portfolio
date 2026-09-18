<?php

namespace Database\Seeders;

use App\Models\Education;
use Illuminate\Database\Seeder;

class EducationSeeder extends Seeder
{
    public function run(): void
    {
        $entries = [
            [
                'degree' => "Bachelor's Degree in Computer/Information Technology Administration and Management",
                'institution' => 'BlueCrest University College',
                'start_date' => '2017-01-01',
                'end_date' => '2021-12-31',
                'order' => 1,
            ],
            [
                'degree' => 'Diploma in Computer Operating Systems & MS Office Suite',
                'institution' => 'Computer Secretarial Institute',
                'start_date' => '2013-01-01',
                'end_date' => '2013-12-31',
                'order' => 2,
            ],
            [
                'degree' => 'High School Diploma',
                'institution' => 'St. Francis Catholic High School',
                'start_date' => '2001-01-01',
                'end_date' => '2015-12-31',
                'order' => 3,
            ],
        ];

        Education::query()->delete();

        foreach ($entries as $entry) {
            Education::create($entry);
        }
    }
}
