<?php

namespace App\Contracts;

use App\Models\Payment;

interface MobileMoneyGateway
{
    /**
     * Kick off a "request to pay" charge on the customer's phone.
     *
     * @return array{reference: string, redirect_url: ?string} The gateway's
     *   own reference for this transaction (used later to poll status), and
     *   a URL to send the customer to when the gateway requires a redirect
     *   step to confirm the charge (null for a pure USSD/PIN push).
     */
    public function requestPayment(Payment $payment): array;

    /**
     * Poll the gateway for the current status of a previously-initiated payment.
     */
    public function checkStatus(Payment $payment): string;
}
