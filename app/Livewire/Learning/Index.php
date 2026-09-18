<?php

namespace App\Livewire\Learning;

use App\Models\Course;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.app')]
class Index extends Component
{
    public function render()
    {
        return view('livewire.learning.index', [
            'courses' => Course::query()->where('status', 'published')->orderBy('order')->get(),
        ]);
    }
}
