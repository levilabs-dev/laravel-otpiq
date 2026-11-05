<?php

declare(strict_types=1);

namespace LeviLabs\LaravelOtpiq\Enums;

enum DeliveryChannel: string
{
    case Sms = 'sms';
    case WhatsApp = 'whatsapp';
    case Telegram = 'telegram';
}
