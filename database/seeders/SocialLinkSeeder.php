<?php

namespace Database\Seeders;

use App\Models\SocialLink;
use Illuminate\Database\Seeder;

class SocialLinkSeeder extends Seeder
{
    public function run(): void
    {
        $links = [
            ['platform' => 'Facebook', 'url' => 'https://web.facebook.com/francis.veteran', 'order' => 1],
            ['platform' => 'X (Twitter)', 'url' => 'https://x.com/veteranfrancis', 'order' => 2],
            ['platform' => 'YouTube', 'url' => 'https://www.youtube.com', 'order' => 3],
            ['platform' => 'LinkedIn', 'url' => 'https://www.linkedin.com/in/francis-landford-a25707a2', 'order' => 4],
            // Placeholder — not a confirmed handle yet. Inactive until verified in /admin/social-links.
            ['platform' => 'Instagram', 'url' => 'https://www.instagram.com/francislandford', 'order' => 5, 'is_active' => false],
        ];

        foreach ($links as $link) {
            SocialLink::updateOrCreate(['platform' => $link['platform']], $link);
        }
    }
}
