<?php

namespace App\Services\Payments;

use App\Contracts\MobileMoneyGateway;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Payment;
use App\Models\User;
use RuntimeException;

class CoursePaymentService
{
    public function __construct(
        private readonly MtnMomoGateway $mtnMomo,
        private readonly OrangeMoneyGateway $orangeMoney,
    ) {}

    /**
     * Create a pending payment and kick off the "request to pay" charge with
     * the chosen gateway. Returns the payment, with gateway_reference and any
     * redirect_url filled in.
     */
    public function initiate(User $user, Course $course, string $gateway, string $phoneNumber): Payment
    {
        if ($course->isFree()) {
            throw new RuntimeException('This course is free - no payment is required.');
        }

        $payment = Payment::create([
            'user_id' => $user->id,
            'course_id' => $course->id,
            'gateway' => $gateway,
            'phone_number' => $phoneNumber,
            'amount' => $course->price,
            'currency' => $course->currency,
            'status' => 'pending',
        ]);

        try {
            $result = $this->gateway($gateway)->requestPayment($payment);
        } catch (\Throwable $e) {
            $payment->update(['status' => 'failed', 'meta' => ['error' => $e->getMessage()]]);

            throw $e;
        }

        $payment->update([
            'gateway_reference' => $result['reference'],
            'meta' => array_filter(['payment_url' => $result['redirect_url'] ?? null]),
        ]);

        return $payment;
    }

    /**
     * Poll the gateway for this payment's current status. When it has just
     * turned successful, enroll the user in the course.
     */
    public function refreshStatus(Payment $payment): string
    {
        if ($payment->status !== 'pending') {
            return $payment->status;
        }

        $status = $this->gateway($payment->gateway)->checkStatus($payment);

        if ($status !== $payment->status) {
            $payment->update(['status' => $status]);

            if ($status === 'successful') {
                Enrollment::firstOrCreate(
                    ['user_id' => $payment->user_id, 'course_id' => $payment->course_id],
                    ['status' => 'active', 'enrolled_at' => now()]
                );
            }
        }

        return $status;
    }

    private function gateway(string $name): MobileMoneyGateway
    {
        return match ($name) {
            'mtn_momo' => $this->mtnMomo,
            'orange_money' => $this->orangeMoney,
            default => throw new RuntimeException("Unknown payment gateway [{$name}]."),
        };
    }
}
