<?php

namespace App\Livewire\Learning;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Lesson as LessonModel;
use App\Models\LessonProgress;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Livewire\Component;

class Lesson extends Component
{
    public Course $course;
    public LessonModel $lesson;
    public ?Enrollment $enrollment = null;

    public function mount(Course $course, LessonModel $lesson): void
    {
        abort_unless($lesson->course_id === $course->id, 404);

        $this->course = $course;
        $this->lesson = $lesson;

        if ($lesson->is_free_preview) {
            $this->enrollment = Auth::check()
                ? Enrollment::where('user_id', Auth::id())->where('course_id', $course->id)->first()
                : null;

            return;
        }

        abort_unless(Auth::check(), 403);

        $this->enrollment = Enrollment::where('user_id', Auth::id())->where('course_id', $course->id)->first();

        abort_unless($this->enrollment, 403);
    }

    public function markComplete(): void
    {
        abort_unless($this->enrollment, 403);

        LessonProgress::firstOrCreate(
            ['enrollment_id' => $this->enrollment->id, 'lesson_id' => $this->lesson->id],
            ['completed_at' => now()]
        );

        $this->enrollment->refresh();
    }

    public function render(): View
    {
        $lessons = $this->course->lessons()->get();
        $currentIndex = $lessons->search(fn ($l) => $l->id === $this->lesson->id);

        return view('livewire.learning.lesson', [
            'lessons' => $lessons,
            'previousLesson' => $currentIndex > 0 ? $lessons[$currentIndex - 1] : null,
            'nextLesson' => $currentIndex < $lessons->count() - 1 ? $lessons[$currentIndex + 1] : null,
            'isComplete' => $this->enrollment?->hasCompletedLesson($this->lesson) ?? false,
        ])->layout('components.layouts.app', [
            'title' => $this->lesson->title.' - '.$this->course->title,
        ]);
    }
}
