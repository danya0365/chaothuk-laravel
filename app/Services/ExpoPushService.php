<?php

namespace App\Services;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Expo push delivery.
 *
 * The backend stores the tokens, and when something happens that a device
 * should hear about right now (a chat message is the first case), this service
 * posts to Expo's push service. Failures are logged, never raised: a broken
 * push channel must not fail the request the push came from.
 */
class ExpoPushService
{
    public const ENDPOINT = 'https://exp.host/--/api/v2/push/send';

    /**
     * Send one Expo push message per device token.
     *
     * @param  Collection<int, string>  $tokens  Expo push tokens, e.g. ExponentPushToken[…]
     */
    public function sendToMany(Collection $tokens, string $title, string $body, array $data = []): void
    {
        if ($tokens->isEmpty()) {
            return;
        }

        $messages = $tokens->map(fn (string $token) => [
            'to' => $token,
            'title' => $title,
            'body' => $body,
            'data' => $data,
            // Chime comes from the device's settings — the backend stays quiet.
            'sound' => null,
            'categoryId' => 'messages',
        ])->all();

        try {
            Http::asJson()
                ->timeout(10)
                ->post(self::ENDPOINT, $messages);
        } catch (\Throwable $error) {
            // A push outage must not roll back the booking, message or anything
            // else the push was announcing.
            Log::warning('Expo push delivery failed: '.$error->getMessage());
        }
    }
}
