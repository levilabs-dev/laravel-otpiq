<?php

declare(strict_types=1);

namespace LeviLabs\LaravelOtpiq\Data\Messaging;

use LeviLabs\LaravelOtpiq\Enums\DeliveryReportType;

final readonly class DeliveryReportConfig
{
    public function __construct(
        public string $webhookUrl,
        public DeliveryReportType $deliveryReportType = DeliveryReportType::Final,
        public ?string $webhookSecret = null,
    ) {}

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return array_filter([
            'webhookUrl' => $this->webhookUrl,
            'deliveryReportType' => $this->deliveryReportType->value,
            'webhookSecret' => $this->webhookSecret,
        ], fn (mixed $value): bool => $value !== null && $value !== '');
    }

    public static function fromConfig(): ?self
    {
        $url = config('otpiq.delivery_report.webhook_url');

        if (! is_string($url) || $url === '') {
            return null;
        }

        $type = config('otpiq.delivery_report.delivery_report_type', 'final');
        $secret = config('otpiq.delivery_report.webhook_secret');

        return new self(
            webhookUrl: $url,
            deliveryReportType: DeliveryReportType::tryFrom((string) $type) ?? DeliveryReportType::Final,
            webhookSecret: is_string($secret) && $secret !== '' ? $secret : null,
        );
    }
}
