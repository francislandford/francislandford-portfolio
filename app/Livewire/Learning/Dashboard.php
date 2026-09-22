<?php

namespace App\Livewire\Learning;

use App\Models\Enrollment;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Dashboard extends Component
{
    public function render()
    {
        $enrollments = Enrollment::where('user_id', Auth::id())
            ->with('course.quiz')
            ->latest('enrolled_at')
            ->get();

        return view('livewire.learning.dashboard', [
            'enrollments' => $enrollments,
        ])->layout('components.layouts.app', [
            'title' => 'My Courses',
            'robots' => 'noindex, follow',
        ]);
    }
}
