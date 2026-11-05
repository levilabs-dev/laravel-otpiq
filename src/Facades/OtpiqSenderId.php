<?php

declare(strict_types=1);

namespace LeviLabs\LaravelOtpiq\Facades;

use Illuminate\Support\Facades\Facade;
use LeviLabs\LaravelOtpiq\Contracts\OtpiqSenderIdServiceContract;
use LeviLabs\LaravelOtpiq\Data\SenderIds\SenderIdData;

/**
 * @method static array<int, SenderIdData> list(?string $project = null)
 *
 * @see OtpiqSenderIdServiceContract
 */
class OtpiqSenderId extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return OtpiqSenderIdServiceContract::class;
    }
}
