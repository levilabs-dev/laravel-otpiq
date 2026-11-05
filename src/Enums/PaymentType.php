<?php

declare(strict_types=1);

namespace LeviLabs\LaravelOtpiq\Enums;

enum PaymentType: string
{
    case Prepaid = 'prepaid';
    case Postpaid = 'postpaid';
}
