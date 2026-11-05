<?php

declare(strict_types=1);

namespace LeviLabs\LaravelOtpiq\Services;

use LeviLabs\LaravelOtpiq\Contracts\OtpiqPricingServiceContract;
use LeviLabs\LaravelOtpiq\Services\Concerns\TalksToOtpiq;

final class OtpiqPricingService implements OtpiqPricingServiceContract
{
    use TalksToOtpiq;

    public function internationalSmsPricing(
        ?int $page = null,
        ?int $limit = null,
        ?string $countryIso = null,
        ?string $project = null,
    ): array {
        $query = array_filter([
            'page' => $page,
            'limit' => $limit,
            'countryIso' => $countryIso,
        ], fn (mixed $value): bool => $value !== null);

        return $this->send('international SMS pricing', 'get', '/international-sms-pricing', $query, $project);
    }
}
