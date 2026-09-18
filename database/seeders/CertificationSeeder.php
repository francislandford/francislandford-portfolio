<?php

namespace Database\Seeders;

use App\Models\Certification;
use Illuminate\Database\Seeder;

class CertificationSeeder extends Seeder
{
    public function run(): void
    {
        $entries = [
            [
                'title' => 'Certificate of Completion — Java Course',
                'issuer' => 'SoloLearn',
                'issued_at' => '2021-04-01',
                'order' => 1,
            ],
            [
                'title' => 'Certificate of Appreciation',
                'issuer' => 'Girls Tech Club',
                'issued_at' => '2024-08-01',
                'order' => 2,
            ],
            [
                'title' => 'Certificate of Participation',
                'issuer' => 'International Conference on Advanced Trends in ICT and Management',
                'issued_at' => '2017-12-01',
                'order' => 3,
            ],
        ];

        Certification::query()->delete();

        foreach ($entries as $entry) {
            Certification::create($entry);
        }
    }
}
