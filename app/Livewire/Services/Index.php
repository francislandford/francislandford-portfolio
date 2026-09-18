<?php

namespace App\Livewire\Services;

use App\Models\Service;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.app')]
class Index extends Component
{
    public function render()
    {
        return view('livewire.services.index', [
            'services' => Service::query()->where('is_active', true)->orderBy('order')->get(),
        ]);
    }
}
