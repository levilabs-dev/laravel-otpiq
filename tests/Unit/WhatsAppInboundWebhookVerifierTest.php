<?php

declare(strict_types=1);

use LeviLabs\LaravelOtpiq\Support\WhatsAppInboundWebhookVerifier;

it('verifies whatsapp inbound signatures', function (): void {
    $secret = 'otpiq_live_sk'.str_repeat('a', 64);
    $timestamp = '1710000000';
    $body = '{"event":"whatsapp.inbound_message.received"}';
    $signature = 'sha256='.hash_hmac('sha256', $timestamp.'.'.$body, $secret);

    expect(WhatsAppInboundWebhookVerifier::verify($body, $timestamp, $signature, $secret))->toBeTrue();
});
