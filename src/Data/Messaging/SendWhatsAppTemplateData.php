<?php

declare(strict_types=1);

namespace LeviLabs\LaravelOtpiq\Data\Messaging;

use LeviLabs\LaravelOtpiq\Enums\Provider;
use LeviLabs\LaravelOtpiq\Support\PhoneNumber;

final readonly class SendWhatsAppTemplateData
{
    /**
     * @param  array<string, mixed>|null  $templateParameters
     */
    public function __construct(
        public string $phoneNumber,
        public string $templateName,
        public string $whatsappAccountId,
        public string $whatsappPhoneId,
        public ?array $templateParameters = null,
        public ?Provider $provider = null,
        public ?DeliveryReportConfig $deliveryReport = null,
    ) {}

    /**
     * @return array<string, mixed>
     */
    public function toPayload(?DeliveryReportConfig $defaultDeliveryReport = null): array
    {
        $provider = $this->provider ?? Provider::WhatsApp;

        $payload = array_filter([
            'phoneNumber' => PhoneNumber::normalize($this->phoneNumber),
            'smsType' => 'whatsapp-template',
            'templateName' => $this->templateName,
            'whatsappAccountId' => $this->whatsappAccountId,
            'whatsappPhoneId' => $this->whatsappPhoneId,
            'templateParameters' => $this->templateParameters,
            'provider' => $provider->value,
        ], fn (mixed $value): bool => $value !== null);

        $delivery = $this->deliveryReport ?? $defaultDeliveryReport;
        if ($delivery !== null) {
            $payload['deliveryReport'] = $delivery->toArray();
        }

        return $payload;
    }
}
