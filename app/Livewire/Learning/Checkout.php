<?php

namespace App\Livewire\Learning;

use App\Models\Course;
use App\Models\Payment;
use App\Services\Payments\CoursePaymentService;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Livewire\Component;

class Checkout extends Component
{
    public Course $course;

    public string $gateway = 'mtn_momo';
    public string $phone_number = '';

    public ?Payment $payment = null;
    public ?string $error = null;

    public function mount(Course $course): void
    {
        abort_unless(Auth::check(), 403);
        abort_if($course->isFree(), 404);

        $this->course = $course;
    }

    public function pay(CoursePaymentService $paymentService): void
    {
        $this->validate([
            'gateway' => ['required', 'in:mtn_momo,orange_money'],
            'phone_number' => ['required', 'string', 'min:8'],
        ]);

        $this->error = null;

        try {
            $this->payment = $paymentService->initiate(Auth::user(), $this->course, $this->gateway, $this->phone_number);
        } catch (\Throwable $e) {
            $this->error = $e->getMessage();
        }
    }

    public function retry(): void
    {
        $this->payment = null;
        $this->error = null;
    }

    public function refreshStatus(CoursePaymentService $paymentService): void
    {
        if (! $this->payment) {
            return;
        }

        try {
            $paymentService->refreshStatus($this->payment);
            $this->payment->refresh();
        } catch (\Throwable $e) {
            $this->error = $e->getMessage();
        }
    }

    public function render(): View
    {
        return view('livewire.learning.checkout')->layout('components.layouts.app', [
            'title' => 'Checkout — '.$this->course->title,
            'robots' => 'noindex, nofollow',
        ]);
    }
}
