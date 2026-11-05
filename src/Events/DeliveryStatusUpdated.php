<?php

declare(strict_types=1);

namespace LeviLabs\LaravelOtpiq\Events;

use LeviLabs\LaravelOtpiq\Data\Messaging\TrackMessageResponse;
use LeviLabs\LaravelOtpiq\Data\Webhooks\DeliveryReportPayload;

final class DeliveryStatusUpdated
{
    public function __construct(
        public readonly string $project,
        public readonly ?TrackMessageResponse $tracking = null,
        public readonly ?DeliveryReportPayload $webhook = null,
    ) {}
}
