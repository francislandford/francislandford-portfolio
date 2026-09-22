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
        $image = $this->service->og_image
            ? \Storage::disk('public')->url($this->service->og_image)
            : null;

        $serviceSchema = array_filter([
            '@context' => 'https://schema.org',
            '@type' => 'Service',
            'name' => $this->service->title,
            'description' => $this->service->summary,
            'image' => $image ? [url($image)] : null,
        ]);

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
            'ogImage' => $image,
            'structuredData' => [$serviceSchema],
        ]);
    }
}
