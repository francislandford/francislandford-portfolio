<?php

namespace App\Livewire\Projects;

use App\Models\Category;
use App\Models\Project;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.app')]
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

        return view('livewire.projects.index', [
            'projects' => $projects,
            'categories' => Category::query()->where('type', 'project')->orderBy('name')->get(),
        ]);
    }
}
