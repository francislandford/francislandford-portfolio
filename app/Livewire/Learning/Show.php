<?php

namespace App\Livewire\Learning;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Setting;
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

        $image = $this->course->og_image
            ? \Storage::disk('public')->url($this->course->og_image)
            : ($this->course->cover_image ? \Storage::disk('public')->url($this->course->cover_image) : null);

        $courseSchema = array_filter([
            '@context' => 'https://schema.org',
            '@type' => 'Course',
            'name' => $this->course->title,
            'description' => $this->course->excerpt ?: $this->course->description,
            'image' => $image ? [url($image)] : null,
            'provider' => [
                '@type' => 'Organization',
                'name' => Setting::get('name'),
                'sameAs' => url('/'),
            ],
            'offers' => [
                '@type' => 'Offer',
                'price' => $this->course->isFree() ? '0' : (string) $this->course->price,
                'priceCurrency' => $this->course->currency ?: 'USD',
                'url' => url()->current(),
            ],
        ]);

        return view('livewire.learning.show', [
            'enrollment' => $enrollment,
        ])->layout('components.layouts.app', [
            'title' => $this->course->meta_title ?: $this->course->title,
            'description' => $this->course->meta_description ?: $this->course->excerpt,
            'ogImage' => $image,
            'ogType' => 'article',
            'structuredData' => [$courseSchema],
        ]);
    }
}
