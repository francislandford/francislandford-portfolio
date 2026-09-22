<?php

namespace App\Livewire\Pages;

use App\Models\Achievement;
use App\Models\Certification;
use App\Models\Education;
use App\Models\Experience;
use App\Models\GalleryItem;
use App\Models\Publication;
use App\Models\Setting;
use App\Models\Skill;
use Livewire\Component;

class About extends Component
{
    public function render()
    {
        $name = Setting::get('name');
        $tagline = Setting::get('tagline');

        return view('livewire.pages.about', [
            'experiences' => Experience::query()->orderByDesc('start_date')->get(),
            'skills' => Skill::query()->where('is_active', true)->orderBy('order')->get(),
            'educations' => Education::query()->orderByDesc('start_date')->get(),
            'certifications' => Certification::query()->orderByDesc('issued_at')->get(),
            'achievements' => Achievement::query()->orderByDesc('date')->orderBy('order')->get(),
            'publications' => Publication::query()->orderByDesc('published_at')->orderBy('order')->get(),
            'galleryItems' => GalleryItem::query()->where('is_active', true)->orderBy('order')->get(),
        ])->layout('components.layouts.app', [
            'title' => 'About',
            'description' => "Learn more about {$name}, {$tagline}: career, skills, education, and achievements.",
        ]);
    }
}
