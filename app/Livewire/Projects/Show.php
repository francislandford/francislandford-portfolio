<?php

namespace App\Livewire\Projects;

use App\Models\Project;
use Illuminate\View\View;
use Livewire\Component;

class Show extends Component
{
    public Project $project;

    public function mount(Project $project): void
    {
        abort_unless($project->status === 'published', 404);

        $this->project = $project->load(['categories', 'skills', 'testimonials']);
    }

    public function render(): View
    {
        return view('livewire.projects.show')->layout('components.layouts.app', [
            'title' => $this->project->meta_title ?: $this->project->title,
            'description' => $this->project->meta_description ?: $this->project->description,
            'ogImage' => $this->project->og_image
                ? \Storage::url($this->project->og_image)
                : ($this->project->cover_image ? \Storage::url($this->project->cover_image) : null),
        ]);
    }
}
