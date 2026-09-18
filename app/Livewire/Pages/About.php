<?php

namespace App\Livewire\Pages;

use App\Models\Achievement;
use App\Models\Certification;
use App\Models\Education;
use App\Models\Experience;
use App\Models\Publication;
use App\Models\Skill;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.app')]
class About extends Component
{
    public function render()
    {
        return view('livewire.pages.about', [
            'experiences' => Experience::query()->orderByDesc('start_date')->get(),
            'skills' => Skill::query()->where('is_active', true)->orderBy('order')->get(),
            'educations' => Education::query()->orderByDesc('start_date')->get(),
            'certifications' => Certification::query()->orderByDesc('issued_at')->get(),
            'achievements' => Achievement::query()->orderByDesc('date')->orderBy('order')->get(),
            'publications' => Publication::query()->orderByDesc('published_at')->orderBy('order')->get(),
        ]);
    }
}
