<?php

namespace App\Livewire\Learning;

use App\Models\Course;
use App\Models\Enrollment;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Livewire\Component;

class Show extends Component
{
    public Course $course;

    public function mount(Course $course): void
    {
        abort_unless($course->status === 'published', 404);

        $this->course = $course->load('lessons', 'quiz');
    }

    public function enrollFree(): mixed
    {
        abort_unless($this->course->isFree(), 403);

        if (! Auth::check()) {
            return redirect()->route('login');
        }

        Enrollment::firstOrCreate(
            ['user_id' => Auth::id(), 'course_id' => $this->course->id],
            ['status' => 'active', 'enrolled_at' => now()]
        );

        return redirect()->route('elearning.dashboard');
    }

    public function render(): View
    {
        $enrollment = Auth::check()
            ? Enrollment::where('user_id', Auth::id())->where('course_id', $this->course->id)->first()
            : null;

        return view('livewire.learning.show', [
            'enrollment' => $enrollment,
        ])->layout('components.layouts.app', [
            'title' => $this->course->meta_title ?: $this->course->title,
            'description' => $this->course->meta_description ?: $this->course->excerpt,
            'ogImage' => $this->course->og_image ? \Storage::url($this->course->og_image) : null,
        ]);
    }
}
