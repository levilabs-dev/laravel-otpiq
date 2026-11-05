<?php

declare(strict_types=1);

namespace LeviLabs\LaravelOtpiq\Contracts;

interface OtpiqPricingServiceContract
{
    /**
     * @return array<string, mixed>
     */
    public function internationalSmsPricing(
        ?int $page = null,
        ?int $limit = null,
        ?string $countryIso = null,
        ?string $project = null,
    ): array;
}
