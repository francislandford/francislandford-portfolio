<?php

namespace App\Livewire\Blog;

use App\Models\Post;
use App\Models\Setting;
use Livewire\Component;

class Index extends Component
{
    public function render()
    {
        $name = Setting::get('name');

        return view('livewire.blog.index', [
            'posts' => Post::query()
                ->where('status', 'published')
                ->where('published_at', '<=', now())
                ->orderByDesc('published_at')
                ->paginate(9),
        ])->layout('components.layouts.app', [
            'title' => 'Blog',
            'description' => "Writing and notes from {$name} on software, projects, and what I'm learning.",
        ]);
    }
}
