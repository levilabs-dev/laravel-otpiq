<?php

declare(strict_types=1);

namespace LeviLabs\LaravelOtpiq\Data\Webhooks;

use LeviLabs\LaravelOtpiq\Enums\DeliveryChannel;
use LeviLabs\LaravelOtpiq\Enums\DeliveryReportType;
use LeviLabs\LaravelOtpiq\Enums\MessageStatus;

final readonly class DeliveryReportPayload
{
    public function __construct(
        public string $smsId,
        public DeliveryReportType $deliveryReportType,
        public bool $isFinal,
        public DeliveryChannel $channel,
        public MessageStatus $status,
        public ?string $reason = null,
        public ?string $senderId = null,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            smsId: (string) ($data['smsId'] ?? ''),
            deliveryReportType: DeliveryReportType::from((string) ($data['deliveryReportType'] ?? 'final')),
            isFinal: (bool) ($data['isFinal'] ?? false),
            channel: DeliveryChannel::from((string) ($data['channel'] ?? 'sms')),
            status: MessageStatus::from((string) ($data['status'] ?? 'pending')),
            reason: isset($data['reason']) ? (string) $data['reason'] : null,
            senderId: isset($data['senderId']) ? (string) $data['senderId'] : null,
        );
    }
}
