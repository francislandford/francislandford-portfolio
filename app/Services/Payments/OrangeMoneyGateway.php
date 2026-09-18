<?php

namespace App\Services\Payments;

use App\Contracts\MobileMoneyGateway;
use App\Models\Payment;
use Illuminate\Support\Facades\Http;
use RuntimeException;

/**
 * Orange Money Web Payment API.
 * https://developer.orange.com/apis/om-webpay
 */
class OrangeMoneyGateway implements MobileMoneyGateway
{
    public function requestPayment(Payment $payment): array
    {
        $config = config('services.orange_money');

        if (empty($config['client_id']) || empty($config['client_secret']) || empty($config['merchant_key'])) {
            throw new RuntimeException('Orange Money credentials are not configured.');
        }

        $token = $this->getAccessToken($config);

        $response = Http::withToken($token)
            ->post("{$config['base_url']}/orange-money-webpay/{$config['region']}/v1/webpayment", [
                'merchant_key' => $config['merchant_key'],
                'currency' => $payment->currency,
                'order_id' => (string) $payment->id,
                'amount' => (string) $payment->amount,
                'return_url' => route('elearning.payment.return', $payment),
                'cancel_url' => route('elearning.payment.cancel', $payment),
                'notif_url' => route('elearning.payment.webhook', ['gateway' => 'orange_money']),
                'lang' => 'en',
                'reference' => "Course enrollment #{$payment->id}",
            ]);

        if (! $response->successful()) {
            throw new RuntimeException('Orange Money web payment initiation failed: '.$response->body());
        }

        // pay_token doubles as our gateway reference for status polling.
        return [
            'reference' => $response->json('pay_token'),
            'redirect_url' => $response->json('payment_url'),
        ];
    }

    public function checkStatus(Payment $payment): string
    {
        $config = config('services.orange_money');
        $token = $this->getAccessToken($config);

        $response = Http::withToken($token)
            ->get("{$config['base_url']}/orange-money-webpay/{$config['region']}/v1/transactionstatus", [
                'order_id' => (string) $payment->id,
                'amount' => (string) $payment->amount,
                'pay_token' => $payment->gateway_reference,
            ]);

        if (! $response->successful()) {
            throw new RuntimeException('Orange Money status check failed: '.$response->body());
        }

        return match ($response->json('status')) {
            'SUCCESS' => 'successful',
            'FAILED' => 'failed',
            default => 'pending',
        };
    }

    private function getAccessToken(array $config): string
    {
        $response = Http::asForm()
            ->withBasicAuth($config['client_id'], $config['client_secret'])
            ->post("{$config['base_url']}/oauth/v3/token", [
                'grant_type' => 'client_credentials',
            ]);

        if (! $response->successful()) {
            throw new RuntimeException('Could not authenticate with Orange Money: '.$response->body());
        }

        return $response->json('access_token');
    }
}
