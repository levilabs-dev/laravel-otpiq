<?php

declare(strict_types=1);

namespace LeviLabs\LaravelOtpiq\Enums;

enum DeliveryReportType: string
{
    case All = 'all';
    case Final = 'final';
}
