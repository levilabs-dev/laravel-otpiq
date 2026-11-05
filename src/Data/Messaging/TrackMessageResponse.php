<?php

declare(strict_types=1);

namespace LeviLabs\LaravelOtpiq\Data\Messaging;

use LeviLabs\LaravelOtpiq\Enums\DeliveryChannel;
use LeviLabs\LaravelOtpiq\Enums\MessageStatus;

final readonly class TrackMessageResponse
{
    /**
     * @param  array<int, ChannelFlowStep>  $channelFlow
     */
    public function __construct(
        public string $smsId,
        public string $phoneNumber,
        public MessageStatus $status,
        public float $cost,
        public bool $isFinalStatus,
        public DeliveryChannel $lastChannel,
        public array $channelFlow,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        /** @var array<int, array<string, mixed>> $flow */
        $flow = is_array($data['channelFlow'] ?? null) ? $data['channelFlow'] : [];

        return new self(
            smsId: (string) ($data['smsId'] ?? ''),
            phoneNumber: (string) ($data['phoneNumber'] ?? ''),
            status: MessageStatus::from((string) ($data['status'] ?? 'pending')),
            cost: (float) ($data['cost'] ?? 0),
            isFinalStatus: (bool) ($data['isFinalStatus'] ?? false),
            lastChannel: DeliveryChannel::from((string) ($data['lastChannel'] ?? 'sms')),
            channelFlow: array_map(
                fn (array $step): ChannelFlowStep => ChannelFlowStep::fromArray($step),
                $flow,
            ),
        );
    }
}
