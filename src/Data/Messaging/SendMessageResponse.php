<?php

declare(strict_types=1);

namespace LeviLabs\LaravelOtpiq\Data\Messaging;

use LeviLabs\LaravelOtpiq\Enums\PaymentType;

final readonly class SendMessageResponse
{
    public function __construct(
        public string $message,
        public string $smsId,
        public float $remainingCredit,
        public float $cost,
        public bool $canCover,
        public PaymentType $paymentType,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            message: (string) ($data['message'] ?? ''),
            smsId: (string) ($data['smsId'] ?? ''),
            remainingCredit: (float) ($data['remainingCredit'] ?? 0),
            cost: (float) ($data['cost'] ?? 0),
            canCover: (bool) ($data['canCover'] ?? false),
            paymentType: PaymentType::from((string) ($data['paymentType'] ?? 'prepaid')),
        );
    }
}
