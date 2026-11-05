<?php

declare(strict_types=1);

namespace LeviLabs\LaravelOtpiq\Facades;

use Illuminate\Support\Facades\Facade;
use LeviLabs\LaravelOtpiq\Contracts\OtpiqWhatsAppResourceServiceContract;

/**
 * @method static array<string, mixed> list(?string $project = null)
 *
 * @see OtpiqWhatsAppResourceServiceContract
 */
class OtpiqWhatsApp extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return OtpiqWhatsAppResourceServiceContract::class;
    }
}
