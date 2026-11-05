<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Event;
use LeviLabs\LaravelOtpiq\Events\DeliveryStatusUpdated;
use LeviLabs\LaravelOtpiq\Support\DeliveryWebhook;

it('dispatches delivery status updated when webhook secret matches', function (): void {
    Event::fake([DeliveryStatusUpdated::class]);

    $payload = [
        'smsId' => 'sms-1234567890abcdef123456',
        'deliveryReportType' => 'final',
        'isFinal' => true,
        'channel' => 'sms',
        'status' => 'delivered',
    ];

    $report = DeliveryWebhook::dispatchIfVerified($payload, 'secret', 'secret');

    expect($report)->not->toBeNull()
        ->and($report?->status->value)->toBe('delivered');

    Event::assertDispatched(DeliveryStatusUpdated::class);
});
