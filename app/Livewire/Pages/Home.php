<?php

namespace App\Livewire\Pages;

use App\Models\GithubDigest;
use App\Models\Project;
use App\Models\Service;
use App\Models\Setting;
use App\Models\Skill;
use App\Models\SocialLink;
use App\Models\Testimonial;
use App\Models\TrustedCompany;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.app')]
class Home extends Component
{
    public function render()
    {
        $address = Setting::get('address', '');
        $addressParts = array_map('trim', explode(',', $address));
        $location = implode(', ', array_slice($addressParts, -2));

        return view('livewire.pages.home', [
            'location' => $location,
            'githubUrl' => Setting::get('github_username') ? 'https://github.com/'.Setting::get('github_username') : null,
            'linkedinUrl' => SocialLink::query()->where('platform', 'LinkedIn')->where('is_active', true)->value('url'),
            'services' => Service::query()->where('is_active', true)->orderBy('order')->get(),
            'featuredProjects' => Project::query()
                ->where('status', 'published')
                ->orderByDesc('is_featured')
                ->orderBy('order')
                ->limit(4)
                ->get(),
            'skills' => Skill::query()->where('is_active', true)->orderBy('order')->get(),
            'testimonials' => Testimonial::query()->where('is_featured', true)->orderBy('order')->get(),
            'trustedCompanies' => TrustedCompany::query()->where('is_active', true)->orderBy('order')->get(),
            'githubDigest' => GithubDigest::query()->latest('generated_at')->first(),
            'stats' => [
                ['label' => 'Years of Experience', 'value' => Setting::get('stat_years_experience')],
                ['label' => 'Projects Completed', 'value' => Setting::get('stat_projects_completed')],
                ['label' => 'Happy Clients', 'value' => Setting::get('stat_happy_clients')],
                ['label' => 'Awards Won', 'value' => Setting::get('stat_awards')],
            ],
        ]);
    }
}
