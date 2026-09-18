<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Project;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    public function run(): void
    {
        $projects = [
            [
                'title' => 'Cargo & Vessel Operations Mobile App (DockMaster Mobile)',
                'client' => 'Bam Global',
                'start_date' => '2026-03-01',
                'description' => 'A mobile application for managing cargo and vessel boarding operations with offline-first functionality and automatic data synchronization.',
                'body' => "Bam Global's port officers work dockside and aboard vessels, exactly where a data connection is least reliable, so DockMaster Mobile was built local-first from the start rather than treating offline support as an afterthought. Every action — logging a vessel arrival, recording cargo onboarded or offloaded, checking a crew member in — writes to the device immediately and queues for sync, so the app never blocks on the network.\n\nBuilt with React Native for a single codebase across iOS and Android, with an idempotent sync queue and field-level conflict resolution so two officers working the same vessel don't overwrite each other's entries. The result is a tool officers can rely on regardless of signal, with data reconciling automatically the moment connectivity returns.",
                'status' => 'published',
                'categories' => ['Mobile Development'],
            ],
            [
                'title' => 'University Management System',
                'client' => 'Assemblies of God University',
                'start_date' => '2026-03-31',
                'description' => 'A comprehensive university management system that handles students, lecturers, courses, roles, and academic operations through a centralized and secure platform.',
                'body' => "Assemblies of God University needed one system to replace scattered spreadsheets and manual processes across student records, lecturer assignments, and course administration. Built on Laravel and MySQL, the platform centralizes academic operations behind role-based access control, so students, lecturers, and administrators each see exactly the tools relevant to them.\n\nThe system handles enrollment, course and lecturer management, and academic record-keeping through a single secure platform, with the data model designed to accommodate the university's existing academic structure rather than forcing the institution to adapt to generic software.",
                'status' => 'published',
                'categories' => ['Development'],
            ],
            [
                'title' => 'Cargo & Vessel Operations Management System (DockMaster)',
                'client' => 'Bam Global',
                'start_date' => '2026-03-31',
                'description' => 'A cargo and vessel operations system that manages boarding, unboarding, and cargo tracking with offline-first mobile support and real-time synchronization.',
                'body' => "DockMaster is the backend counterpart to DockMaster Mobile: a Laravel and MySQL system that gives Bam Global's operations team a single source of truth for vessel boarding, unboarding, and cargo tracking. It's built around the same offline-first philosophy as the mobile app — field data syncs in through the same idempotent operation model, so records stay consistent whether they originate from a dockside device or the operations desk.\n\nReal-time synchronization means office staff see field updates as they arrive rather than waiting on manual reconciliation, and the system's audit trail gives Bam Global visibility into cargo movement that scattered paper logs never could.",
                'status' => 'published',
                'categories' => ['Development'],
            ],
            [
                'title' => 'FrontPage Africa Digital Platform',
                'client' => 'FrontPage Africa Online',
                'start_date' => '2022-10-10',
                'live_url' => 'https://frontpageafricaonline.com',
                'description' => 'A modern, high-performance news website built to deliver real-time updates, manage digital content efficiently, and provide a seamless reading experience across devices.',
                'body' => "FrontPage Africa needed a platform that could keep pace with news as it breaks while staying fast and readable under real newsroom traffic. The site is built for editorial efficiency on the backend — a content management workflow that lets the editorial team publish and update stories quickly — and for speed and clarity on the frontend, with a responsive reading experience across desktop and mobile.\n\nThe platform has been live and in production use since 2022, serving as the digital home for one of Liberia's prominent news outlets.",
                'status' => 'published',
                'categories' => ['Development', 'Design'],
            ],
            [
                'title' => 'Tall Youths Foundation Digital Platform',
                'client' => 'Tall Youths Foundation',
                'live_url' => 'https://tallyouthsfoundation.org',
                'description' => 'A modern website for a youth-focused nonprofit organization, designed to showcase initiatives, engage supporters, and improve outreach.',
                'body' => "Tall Youths Foundation needed a digital presence that could do double duty: tell the story of its youth-focused initiatives clearly to newcomers, and give existing supporters an easy way to stay engaged. The site is built around the organization's programs and impact, with a clean, modern design that puts the foundation's work front and center rather than burying it under generic nonprofit-template styling.\n\nThe result is a platform the foundation can point people to with confidence, whether that's a potential donor, a partner organization, or a young person looking to get involved.",
                'status' => 'published',
                'categories' => ['Development'],
            ],
        ];

        foreach ($projects as $index => $data) {
            $categoryNames = $data['categories'];
            unset($data['categories']);
            $data['order'] = $index + 1;

            $project = Project::updateOrCreate(['title' => $data['title']], $data);

            $categoryIds = collect($categoryNames)->map(
                fn (string $name) => Category::firstOrCreate(['type' => 'project', 'name' => $name])->id
            );

            $project->categories()->sync($categoryIds);
        }
    }
}
