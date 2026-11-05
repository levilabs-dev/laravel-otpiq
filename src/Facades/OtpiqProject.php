<?php

declare(strict_types=1);

namespace LeviLabs\LaravelOtpiq\Facades;

use Illuminate\Support\Facades\Facade;
use LeviLabs\LaravelOtpiq\Contracts\OtpiqProjectServiceContract;
use LeviLabs\LaravelOtpiq\Data\Project\ProjectInfo;

/**
 * @method static ProjectInfo info(?string $project = null)
 *
 * @see OtpiqProjectServiceContract
 */
class OtpiqProject extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return OtpiqProjectServiceContract::class;
    }
}
