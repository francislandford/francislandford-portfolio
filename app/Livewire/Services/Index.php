<?php

namespace App\Livewire\Services;

use App\Models\Service;
use App\Models\Setting;
use Livewire\Component;

class Index extends Component
{
    public function render()
    {
        $name = Setting::get('name');

        return view('livewire.services.index', [
            'services' => Service::query()->where('is_active', true)->orderBy('order')->get(),
        ])->layout('components.layouts.app', [
            'title' => 'Services',
            'description' => "Tailored digital solutions from {$name} to help you achieve your goals.",
        ]);
    }
}
