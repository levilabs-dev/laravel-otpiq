<?php

declare(strict_types=1);

namespace LeviLabs\LaravelOtpiq\Support;

/**
 * Verifies OTPIQ SMS delivery webhooks using X-OTPIQ-Webhook-Secret.
 */
final class DeliveryWebhookVerifier
{
    public static function verify(?string $receivedSecret, string $expectedSecret): bool
    {
        $receivedSecret = trim((string) $receivedSecret);
        $expectedSecret = trim($expectedSecret);

        if ($receivedSecret === '' || $expectedSecret === '') {
            return false;
        }

        return hash_equals($expectedSecret, $receivedSecret);
    }
}
