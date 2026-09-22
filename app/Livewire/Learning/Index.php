<?php

namespace App\Livewire\Learning;

use App\Models\Course;
use App\Models\Setting;
use Livewire\Component;

class Index extends Component
{
    public function render()
    {
        $name = Setting::get('name');

        return view('livewire.learning.index', [
            'courses' => Course::query()->where('status', 'published')->orderBy('order')->get(),
        ])->layout('components.layouts.app', [
            'title' => 'Courses',
            'description' => "Learn what {$name} has learned building software — courses and tutorials, free and paid.",
        ]);
    }
}
