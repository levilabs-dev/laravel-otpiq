<?php

declare(strict_types=1);

namespace LeviLabs\LaravelOtpiq\Data\SenderIds;

final readonly class SenderIdData
{
    /**
     * @param  array<string, int|float>|null  $pricePerSms
     */
    public function __construct(
        public string $id,
        public string $senderId,
        public string $status,
        public ?array $pricePerSms = null,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        /** @var array<string, int|float>|null $prices */
        $prices = is_array($data['pricePerSms'] ?? null) ? $data['pricePerSms'] : null;

        return new self(
            id: (string) ($data['_id'] ?? ''),
            senderId: (string) ($data['senderId'] ?? ''),
            status: (string) ($data['status'] ?? ''),
            pricePerSms: $prices,
        );
    }
}
