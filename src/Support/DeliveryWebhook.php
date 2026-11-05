<?php

declare(strict_types=1);

namespace LeviLabs\LaravelOtpiq\Support;

use Illuminate\Support\Facades\Event;
use LeviLabs\LaravelOtpiq\Data\Webhooks\DeliveryReportPayload;
use LeviLabs\LaravelOtpiq\Events\DeliveryStatusUpdated;

/**
 * Parses and optionally dispatches OTPIQ delivery webhook payloads.
 */
final class DeliveryWebhook
{
    /**
     * @param  array<string, mixed>  $payload
     */
    public static function payloadFromArray(array $payload): DeliveryReportPayload
    {
        return DeliveryReportPayload::fromArray($payload);
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    public static function dispatchIfVerified(
        array $payload,
        ?string $receivedSecret,
        string $expectedSecret,
        string $project = 'default',
    ): ?DeliveryReportPayload {
        if (! DeliveryWebhookVerifier::verify($receivedSecret, $expectedSecret)) {
            return null;
        }

        $report = self::payloadFromArray($payload);

        Event::dispatch(new DeliveryStatusUpdated(
            project: $project,
            webhook: $report,
        ));

        return $report;
    }
}
