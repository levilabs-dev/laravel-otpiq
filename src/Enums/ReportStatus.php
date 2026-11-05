<?php

declare(strict_types=1);

namespace LeviLabs\LaravelOtpiq\Enums;

enum ReportStatus: string
{
    case Available = 'available';
    case Expired = 'expired';
    case Deleted = 'deleted';
}
