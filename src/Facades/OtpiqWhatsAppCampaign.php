<?php

declare(strict_types=1);

namespace LeviLabs\LaravelOtpiq\Facades;

use Illuminate\Support\Facades\Facade;
use LeviLabs\LaravelOtpiq\Contracts\OtpiqWhatsAppCampaignServiceContract;

/**
 * @mixin OtpiqWhatsAppCampaignServiceContract
 */
class OtpiqWhatsAppCampaign extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return OtpiqWhatsAppCampaignServiceContract::class;
    }
}
