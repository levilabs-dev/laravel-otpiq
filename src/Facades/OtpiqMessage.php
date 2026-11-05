<?php

declare(strict_types=1);

namespace LeviLabs\LaravelOtpiq\Facades;

use Illuminate\Support\Facades\Facade;
use LeviLabs\LaravelOtpiq\Contracts\OtpiqMessagingServiceContract;
use LeviLabs\LaravelOtpiq\Data\Messaging\SendCustomMessageData;
use LeviLabs\LaravelOtpiq\Data\Messaging\SendMessageResponse;
use LeviLabs\LaravelOtpiq\Data\Messaging\SendVerificationData;
use LeviLabs\LaravelOtpiq\Data\Messaging\SendWhatsAppTemplateData;
use LeviLabs\LaravelOtpiq\Data\Messaging\TrackMessageResponse;

/**
 * @method static SendMessageResponse sendVerification(SendVerificationData $data, ?string $project = null)
 * @method static SendMessageResponse sendCustom(SendCustomMessageData $data, ?string $project = null)
 * @method static SendMessageResponse sendWhatsAppTemplate(SendWhatsAppTemplateData $data, ?string $project = null)
 * @method static TrackMessageResponse track(string $smsId, ?string $project = null)
 *
 * @see OtpiqMessagingServiceContract
 */
class OtpiqMessage extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return OtpiqMessagingServiceContract::class;
    }
}
