<?php

declare(strict_types=1);

namespace LeviLabs\LaravelOtpiq\Exceptions;

final class OtpiqProjectException extends OtpiqException
{
    public static function unknownProject(string $project): self
    {
        return new self("Unknown OTPIQ project [{$project}].");
    }

    public static function missingApiKey(string $project): self
    {
        return new self("OTPIQ project [{$project}] is missing an api_key.");
    }
}
