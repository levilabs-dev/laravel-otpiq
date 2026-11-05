<?php

declare(strict_types=1);

namespace LeviLabs\LaravelOtpiq\Facades;

use Illuminate\Support\Facades\Facade;
use LeviLabs\LaravelOtpiq\Contracts\OtpiqReportServiceContract;

/**
 * @mixin OtpiqReportServiceContract
 */
class OtpiqReport extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return OtpiqReportServiceContract::class;
    }
}
