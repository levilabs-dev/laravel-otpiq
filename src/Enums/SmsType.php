<?php

declare(strict_types=1);

namespace LeviLabs\LaravelOtpiq\Enums;

enum SmsType: string
{
    case Verification = 'verification';
    case Custom = 'custom';
    case WhatsAppTemplate = 'whatsapp-template';
}
