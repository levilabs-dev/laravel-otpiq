<?php

declare(strict_types=1);

use LeviLabs\LaravelOtpiq\Support\PhoneNumber;

it('normalizes phone numbers to digits only', function (): void {
    expect(PhoneNumber::normalize('+964 750 123 4567'))->toBe('9647501234567');
});

it('rejects empty phone numbers', function (): void {
    PhoneNumber::normalize('---');
})->throws(InvalidArgumentException::class);
