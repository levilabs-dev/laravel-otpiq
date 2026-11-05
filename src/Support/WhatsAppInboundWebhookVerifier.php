<?php

declare(strict_types=1);

namespace LeviLabs\LaravelOtpiq\Support;

/**
 * Verifies WhatsApp inbound webhooks: HMAC-SHA256 over "{timestamp}.{rawBody}".
 */
final class WhatsAppInboundWebhookVerifier
{
    public static function verify(
        string $rawBody,
        string $timestamp,
        string $signatureHeader,
        string $webhookSecret,
    ): bool {
        $timestamp = trim($timestamp);
        $webhookSecret = trim($webhookSecret);
        $signatureHeader = trim($signatureHeader);

        if ($rawBody === '' || $timestamp === '' || $signatureHeader === '' || $webhookSecret === '') {
            return false;
        }

        if (! str_starts_with(strtolower($signatureHeader), 'sha256=')) {
            return false;
        }

        $provided = substr($signatureHeader, 7);
        $expected = hash_hmac('sha256', $timestamp.'.'.$rawBody, $webhookSecret);

        return hash_equals($expected, $provided);
    }
}
