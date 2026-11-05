<?php

declare(strict_types=1);

use LeviLabs\LaravelOtpiq\Support\DeliveryWebhookVerifier;

it('verifies matching webhook secrets', function (): void {
    expect(DeliveryWebhookVerifier::verify('secret-123', 'secret-123'))->toBeTrue();
});

it('rejects mismatched webhook secrets', function (): void {
    expect(DeliveryWebhookVerifier::verify('wrong', 'secret-123'))->toBeFalse();
});
