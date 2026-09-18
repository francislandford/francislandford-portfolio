<?php

namespace App\Livewire\Learning;

use App\Models\Enrollment;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.app')]
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
        ]);
    }
}
