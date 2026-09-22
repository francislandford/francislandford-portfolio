<?php

namespace App\Livewire\Projects;

use App\Models\Category;
use App\Models\Project;
use App\Models\Setting;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    #[Url]
    public ?string $category = null;

    public function selectCategory(?string $slug): void
    {
        $this->category = $slug;
        $this->resetPage();
    }

    public function render()
    {
        $projects = Project::query()
            ->where('status', 'published')
            ->when($this->category, fn ($query) => $query->whereHas(
                'categories',
                fn ($q) => $q->where('slug', $this->category)
            ))
            ->orderByDesc('is_featured')
            ->orderBy('order')
            ->paginate(9);

        $name = Setting::get('name');

        return view('livewire.projects.index', [
            'projects' => $projects,
            'categories' => Category::query()->where('type', 'project')->orderBy('name')->get(),
        ])->layout('components.layouts.app', [
            'title' => 'Projects',
            'description' => "A look at what {$name} has been building — design, development, and mobile projects.",
        ]);
    }
}
