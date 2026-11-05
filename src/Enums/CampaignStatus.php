<?php

declare(strict_types=1);

namespace LeviLabs\LaravelOtpiq\Enums;

enum CampaignStatus: string
{
    case Draft = 'draft';
    case Scheduled = 'scheduled';
    case Running = 'running';
    case Completed = 'completed';
    case Failed = 'failed';
}
