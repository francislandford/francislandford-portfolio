<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        $services = [
            [
                'title' => 'Mobile Applications Development',
                'summary' => 'Powerful and user-friendly mobile apps for iOS and Android.',
                'body' => "I build mobile apps with React Native, so the same codebase ships to iOS and Android without sacrificing a native feel. My focus is on apps that hold up outside ideal conditions: reliable local storage, graceful handling of dropped connections, and sync logic that catches up automatically once a connection returns — the same approach behind DockMaster Mobile, built for field teams working where signal is inconsistent.\n\nEvery build includes proper state management, clean navigation patterns, and integration with whatever backend the project needs, usually a Laravel or Express API. I test on real devices, not just simulators, and I stay involved through App Store and Play Store submission so the app actually reaches your users.",
                'order' => 1,
            ],
            [
                'title' => 'Web Development',
                'summary' => 'Custom, responsive, and scalable websites for your business.',
                'body' => "Every site I build is custom-coded, not assembled from a page builder. My core stack is Laravel and MySQL on the backend, with React where the frontend needs to be genuinely interactive, from marketing sites and news platforms to multi-role management systems with real business logic behind them.\n\nThat means thinking about the data model and the roles and permissions before a single page is styled, so the system holds up as it grows — the same discipline behind a university-wide management platform handling students, lecturers, and courses through one centralized system, and a news platform delivering real-time updates to a national audience. Every project is responsive by default, built for performance, and structured so it's straightforward to maintain and extend later.",
                'order' => 2,
            ],
            [
                'title' => 'UI/UX Design',
                'summary' => 'Intuitive, user-friendly, and visually appealing designs.',
                'body' => "Good design starts with understanding what someone is actually trying to accomplish, not just making a screen look polished. I work through wireframes and prototypes before writing a line of production code, so the structure of an experience gets tested and refined while it's still cheap to change.\n\nI care about consistency — a real design system with reusable components, not one-off screens — and about accessibility, so the result works for as many people as possible, not just the easiest case. The goal is always an interface that gets out of the user's way and lets them do what they came to do.",
                'order' => 3,
            ],
            [
                'title' => 'SEO Optimization',
                'summary' => 'Boost your visibility and rank higher on search engines.',
                'body' => "SEO work that actually moves the needle starts with the technical fundamentals: fast page loads, clean semantic markup, proper structured data, and URLs and sitemaps search engines can actually work with. I handle that foundation first, because no amount of content strategy fixes a site that's slow or badly structured.\n\nFrom there it's on-page optimization — meta titles and descriptions, heading structure, internal linking — aligned with the keywords that matter for your business, plus ongoing monitoring so rankings are a trend I'm watching, not a one-time project.",
                'order' => 4,
            ],
            [
                'title' => 'Content Creation',
                'summary' => "Engaging and creative content tailored to your brand's voice.",
                'body' => "Whether it's blog posts, technical documentation, or marketing copy, I write content that sounds like your brand, not like a template. That starts with understanding who's actually reading it and what they need from the page, then writing to that, clearly and without unnecessary filler.\n\nI work well alongside a broader web or SEO project, since content that's written with the site's structure and search goals in mind performs better than content bolted on afterward. The output is always something you could hand to another writer as a reference for tone.",
                'order' => 5,
            ],
        ];

        foreach ($services as $service) {
            Service::updateOrCreate(['title' => $service['title']], $service);
        }
    }
}
