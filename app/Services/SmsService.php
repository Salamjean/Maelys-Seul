<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SmsService
{
    /**
     * Envoie un SMS via l'API Yellika (1smsafrica)
     *
     * @param string $phone
     * @param string $message
     * @return bool
     */
    public function sendSms(string $phone, string $message): bool
    {
        try {
            $apiUrl = env('YELLIKA_API_URL') . 'sms/send';
            $apiKey = env('YELLIKA_API_KEY');
            $senderId = env('YELLIKA_SENDER_ID');

            // Nettoyage rigoureux du numéro
            $phone = preg_replace('/[^0-9]/', '', $phone);

            $response = Http::withToken($apiKey)->withHeaders([
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ])->post($apiUrl, [
                'recipient' => $phone,
                'sender_id' => $senderId,
                'type' => 'plain',
                'message' => $message
            ]);

            if ($response->successful()) {
                return true;
            }

            Log::error("Erreur API Yellika (Code " . $response->status() . "): " . $response->body());
            return false;

        } catch (\Exception $e) {
            Log::error("Exception envoi SMS Yellika: " . $e->getMessage());
            return false;
        }
    }
}
