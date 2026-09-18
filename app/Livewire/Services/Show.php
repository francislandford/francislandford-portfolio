<?php

namespace App\Livewire\Services;

use App\Models\Service;
use Illuminate\View\View;
use Livewire\Component;

class Show extends Component
{
    public Service $service;

    public function mount(Service $service): void
    {
        abort_unless($service->is_active, 404);

        $this->service = $service;
    }

    public function render(): View
    {
        return view('livewire.services.show', [
            'otherServices' => Service::query()
                ->where('is_active', true)
                ->where('id', '!=', $this->service->id)
                ->orderBy('order')
                ->limit(3)
                ->get(),
        ])->layout('components.layouts.app', [
            'title' => $this->service->meta_title ?: $this->service->title,
            'description' => $this->service->meta_description ?: $this->service->summary,
            'ogImage' => $this->service->og_image ? \Storage::url($this->service->og_image) : null,
        ]);
    }
}
