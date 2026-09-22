<?php

namespace App\Livewire\Learning;

use App\Models\Certificate as CertificateModel;
use App\Models\Course;
use App\Models\Enrollment;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Livewire\Component;

class Certificate extends Component
{
    public Course $course;
    public Enrollment $enrollment;
    public CertificateModel $certificate;

    public function mount(Course $course): void
    {
        abort_unless(Auth::check(), 403);

        $this->course = $course;
        $this->enrollment = Enrollment::where('user_id', Auth::id())->where('course_id', $course->id)->firstOrFail();

        abort_unless($this->enrollment->isEligibleForCertificate(), 403);

        $this->certificate = CertificateModel::firstOrCreate(
            ['enrollment_id' => $this->enrollment->id],
            ['issued_at' => now()]
        );

        if ($this->enrollment->status !== 'completed') {
            $this->enrollment->update(['status' => 'completed', 'completed_at' => now()]);
        }
    }

    public function render(): View
    {
        return view('livewire.learning.certificate')->layout('components.layouts.app', [
            'title' => 'Certificate - '.$this->course->title,
            'robots' => 'noindex, follow',
        ]);
    }
}
