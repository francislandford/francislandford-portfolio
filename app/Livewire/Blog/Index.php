<?php

namespace App\Livewire\Blog;

use App\Models\Post;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.app')]
class Index extends Component
{
    public function render()
    {
        return view('livewire.blog.index', [
            'posts' => Post::query()
                ->where('status', 'published')
                ->where('published_at', '<=', now())
                ->orderByDesc('published_at')
                ->paginate(9),
        ]);
    }
}
