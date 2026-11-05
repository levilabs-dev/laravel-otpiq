<?php

declare(strict_types=1);

namespace LeviLabs\LaravelOtpiq\Facades;

use Illuminate\Support\Facades\Facade;
use LeviLabs\LaravelOtpiq\Contracts\OtpiqPricingServiceContract;

/**
 * @method static array<string, mixed> internationalSmsPricing(?int $page = null, ?int $limit = null, ?string $countryIso = null, ?string $project = null)
 *
 * @see OtpiqPricingServiceContract
 */
class OtpiqPricing extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return OtpiqPricingServiceContract::class;
    }
}
