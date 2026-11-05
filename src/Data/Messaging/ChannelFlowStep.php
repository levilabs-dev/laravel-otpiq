<?php

declare(strict_types=1);

namespace LeviLabs\LaravelOtpiq\Data\Messaging;

use LeviLabs\LaravelOtpiq\Enums\DeliveryChannel;

final readonly class ChannelFlowStep
{
    public function __construct(
        public DeliveryChannel $channel,
        public bool $tried,
        public ?bool $success = null,
        public ?string $error = null,
        public ?bool $refunded = null,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            channel: DeliveryChannel::from((string) ($data['channel'] ?? 'sms')),
            tried: (bool) ($data['tried'] ?? false),
            success: array_key_exists('success', $data) ? (is_bool($data['success']) ? $data['success'] : null) : null,
            error: isset($data['error']) ? (string) $data['error'] : null,
            refunded: array_key_exists('refunded', $data) ? (is_bool($data['refunded']) ? $data['refunded'] : null) : null,
        );
    }
}
