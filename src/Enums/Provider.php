<?php

declare(strict_types=1);

namespace LeviLabs\LaravelOtpiq\Enums;

enum Provider: string
{
    case Sms = 'sms';
    case WhatsApp = 'whatsapp';
    case Telegram = 'telegram';
    case WhatsAppSms = 'whatsapp-sms';
    case TelegramSms = 'telegram-sms';
    case WhatsAppTelegramSms = 'whatsapp-telegram-sms';
}
