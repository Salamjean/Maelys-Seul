<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WaveService
{
    protected string $apiKey;
    protected string $baseUrl;
    protected string $webhookSecret;

    public function __construct()
    {
        $this->apiKey = config('services.wave.api_key');
        $this->baseUrl = config('services.wave.base_url');
        $this->webhookSecret = config('services.wave.webhook_secret');
    }

    /**
     * Crée une session de paiement Wave
     */
    public function createCheckoutSession(float $amount, string $clientReference, string $successUrl, string $errorUrl)
    {
        try {
            $response = Http::withToken($this->apiKey)
                ->when(config('app.env') === 'local', function ($http) {
                    return $http->withoutVerifying();
                })
                ->post($this->baseUrl . '/checkout/sessions', [
                    'amount' => (int) $amount,
                    'currency' => 'XOF',
                    'error_url' => str_replace('http://', 'https://', $errorUrl),
                    'success_url' => str_replace('http://', 'https://', $successUrl),
                    'client_reference' => $clientReference,
                ]);

            if ($response->successful()) {
                return $response->json();
            }

            Log::error('Wave API Error: ' . $response->body());
            return null;
        } catch (\Exception $e) {
            Log::error('Wave Service Exception: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Vérifie la signature du Webhook Wave
     */
    public function verifyWebhookSignature(string $signatureHeader, string $payload): bool
    {
        if (empty($signatureHeader)) {
            Log::warning('Wave Webhook: SignatureHeader manquante');
            return false;
        }

        // Le format est t=timestamp,v1=signature
        $parts = explode(',', $signatureHeader);
        $timestamp = '';
        $v1_received = '';

        foreach ($parts as $part) {
            $part = trim($part);
            if (str_starts_with($part, 't=')) {
                $timestamp = substr($part, 2);
            } elseif (str_starts_with($part, 'v1=')) {
                $v1_received = substr($part, 3);
            }
        }

        if (empty($timestamp) || empty($v1_received)) {
            Log::warning('Wave Webhook: Format de signature invalide', ['header' => $signatureHeader]);
            return false;
        }

        $computedSignature = hash_hmac('sha256', $timestamp . $payload, $this->webhookSecret);

        $isValid = hash_equals($computedSignature, $v1_received);

        if (!$isValid) {
            Log::error('Wave Signature Mismatch', [
                'timestamp' => $timestamp,
                'v1_received' => $v1_received,
                'v1_expected' => $computedSignature,
                'secret_preview' => substr($this->webhookSecret, 0, 10) . '...'
            ]);
            
            // BYPASS POUR LE TEST LOCAL UNIQUEMENT
            if (config('app.env') === 'local') {
                Log::warning('⚠️ BYPASS de la signature Wave (Mode Local actif)');
                return true; 
            }
        }

        return $isValid;
    }
}
