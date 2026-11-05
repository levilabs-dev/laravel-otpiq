<?php

declare(strict_types=1);

namespace LeviLabs\LaravelOtpiq\Listeners;

use LeviLabs\LaravelOtpiq\Data\Messaging\TrackMessageResponse;
use LeviLabs\LaravelOtpiq\Events\DeliveryStatusUpdated;
use LeviLabs\LaravelOtpiq\Events\MessageSent;
use LeviLabs\LaravelOtpiq\Models\OtpiqMessage;

final class PersistOtpiqMessage
{
    public function onSent(MessageSent $event): void
    {
        OtpiqMessage::query()->updateOrCreate(
            ['sms_id' => $event->response->smsId],
            [
                'project' => $event->project,
                'phone_number' => $event->phoneNumber,
                'sms_type' => $event->smsType,
                'provider' => $event->provider,
                'status' => 'pending',
                'cost' => $event->response->cost,
                'is_final' => false,
                'payment_type' => $event->response->paymentType->value,
            ],
        );
    }

    public function onDeliveryUpdated(DeliveryStatusUpdated $event): void
    {
        if ($event->tracking !== null) {
            $this->persistTracking($event->project, $event->tracking);

            return;
        }

        if ($event->webhook !== null) {
            OtpiqMessage::query()->updateOrCreate(
                ['sms_id' => $event->webhook->smsId],
                [
                    'project' => $event->project,
                    'status' => $event->webhook->status->value,
                    'is_final' => $event->webhook->isFinal,
                    'last_channel' => $event->webhook->channel->value,
                    'tracked_at' => now(),
                ],
            );
        }
    }

    private function persistTracking(string $project, TrackMessageResponse $tracking): void
    {
        OtpiqMessage::query()->updateOrCreate(
            ['sms_id' => $tracking->smsId],
            [
                'project' => $project,
                'phone_number' => $tracking->phoneNumber,
                'status' => $tracking->status->value,
                'cost' => $tracking->cost,
                'is_final' => $tracking->isFinalStatus,
                'last_channel' => $tracking->lastChannel->value,
                'channel_flow' => array_map(
                    fn ($step): array => [
                        'channel' => $step->channel->value,
                        'tried' => $step->tried,
                        'success' => $step->success,
                        'error' => $step->error,
                        'refunded' => $step->refunded,
                    ],
                    $tracking->channelFlow,
                ),
                'tracked_at' => now(),
            ],
        );
    }
}
