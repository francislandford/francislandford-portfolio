<?php

namespace App\Services\Payments;

use App\Contracts\MobileMoneyGateway;
use App\Models\Payment;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use RuntimeException;

/**
 * MTN Mobile Money "Collections" API (Request to Pay).
 * https://momodeveloper.mtn.com
 */
class MtnMomoGateway implements MobileMoneyGateway
{
    public function requestPayment(Payment $payment): array
    {
        $config = config('services.mtn_momo');

        if (empty($config['subscription_key']) || empty($config['api_user']) || empty($config['api_key'])) {
            throw new RuntimeException('MTN MoMo credentials are not configured.');
        }

        $token = $this->getAccessToken($config);
        $reference = (string) Str::uuid();

        $response = Http::withToken($token)
            ->withHeaders([
                'X-Reference-Id' => $reference,
                'X-Target-Environment' => $config['target_environment'],
                'Ocp-Apim-Subscription-Key' => $config['subscription_key'],
            ])
            ->post("{$config['base_url']}/collection/v1_0/requesttopay", [
                'amount' => (string) $payment->amount,
                'currency' => $payment->currency,
                'externalId' => (string) $payment->id,
                'payer' => [
                    'partyIdType' => 'MSISDN',
                    'partyId' => $this->normalizePhone($payment->phone_number),
                ],
                'payerMessage' => "Payment for {$payment->course->title}",
                'payeeNote' => "Course enrollment #{$payment->id}",
            ]);

        if ($response->status() !== 202) {
            throw new RuntimeException('MTN MoMo request to pay failed: '.$response->body());
        }

        return ['reference' => $reference, 'redirect_url' => null];
    }

    public function checkStatus(Payment $payment): string
    {
        $config = config('services.mtn_momo');
        $token = $this->getAccessToken($config);

        $response = Http::withToken($token)
            ->withHeaders([
                'X-Target-Environment' => $config['target_environment'],
                'Ocp-Apim-Subscription-Key' => $config['subscription_key'],
            ])
            ->get("{$config['base_url']}/collection/v1_0/requesttopay/{$payment->gateway_reference}");

        if (! $response->successful()) {
            throw new RuntimeException('MTN MoMo status check failed: '.$response->body());
        }

        return match ($response->json('status')) {
            'SUCCESSFUL' => 'successful',
            'FAILED' => 'failed',
            default => 'pending',
        };
    }

    private function getAccessToken(array $config): string
    {
        $response = Http::withBasicAuth($config['api_user'], $config['api_key'])
            ->withHeaders(['Ocp-Apim-Subscription-Key' => $config['subscription_key']])
            ->post("{$config['base_url']}/collection/token/");

        if (! $response->successful()) {
            throw new RuntimeException('Could not authenticate with MTN MoMo: '.$response->body());
        }

        return $response->json('access_token');
    }

    private function normalizePhone(string $phone): string
    {
        return preg_replace('/[^0-9]/', '', $phone);
    }
}
