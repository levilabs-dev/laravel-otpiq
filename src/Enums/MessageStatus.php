<?php

declare(strict_types=1);

namespace LeviLabs\LaravelOtpiq\Enums;

enum MessageStatus: string
{
    case Pending = 'pending';
    case Sent = 'sent';
    case Delivered = 'delivered';
    case Read = 'read';
    case Failed = 'failed';

    public function isTerminal(): bool
    {
        return match ($this) {
            self::Delivered, self::Read, self::Failed => true,
            self::Pending, self::Sent => false,
        };
    }
}
