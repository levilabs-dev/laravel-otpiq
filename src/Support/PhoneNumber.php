<?php

declare(strict_types=1);

namespace LeviLabs\LaravelOtpiq\Support;

use InvalidArgumentException;

final class PhoneNumber
{
    /**
     * OTPIQ expects digits only (typically 964… without spaces or +).
     */
    public static function normalize(string $phone): string
    {
        $digits = preg_replace('/\D+/', '', $phone) ?? '';

        if ($digits === '') {
            throw new InvalidArgumentException('Phone number must contain digits.');
        }

        if (str_starts_with($digits, '00')) {
            $digits = substr($digits, 2);
        }

        return $digits;
    }
}
