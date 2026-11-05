<?php

declare(strict_types=1);

namespace LeviLabs\LaravelOtpiq\Data\Messaging;

use LeviLabs\LaravelOtpiq\Enums\Provider;
use LeviLabs\LaravelOtpiq\Support\PhoneNumber;

final readonly class SendCustomMessageData
{
    public function __construct(
        public string $phoneNumber,
        public string $customMessage,
        public ?string $senderId = null,
        public ?Provider $provider = null,
        public ?string $whatsappAccountId = null,
        public ?string $whatsappPhoneId = null,
        public ?string $templateName = null,
        public ?DeliveryReportConfig $deliveryReport = null,
    ) {}

    /**
     * @return array<string, mixed>
     */
    public function toPayload(?DeliveryReportConfig $defaultDeliveryReport = null): array
    {
        $provider = $this->provider ?? Provider::tryFrom((string) config('otpiq.defaults.provider', 'whatsapp-telegram-sms'));

        $payload = array_filter([
            'phoneNumber' => PhoneNumber::normalize($this->phoneNumber),
            'smsType' => 'custom',
            'customMessage' => $this->customMessage,
            'senderId' => $this->senderId,
            'provider' => $provider?->value,
            'whatsappAccountId' => $this->whatsappAccountId,
            'whatsappPhoneId' => $this->whatsappPhoneId,
            'templateName' => $this->templateName,
        ], fn (mixed $value): bool => $value !== null && $value !== '');

        $delivery = $this->deliveryReport ?? $defaultDeliveryReport;
        if ($delivery !== null) {
            $payload['deliveryReport'] = $delivery->toArray();
        }

        return $payload;
    }
}
