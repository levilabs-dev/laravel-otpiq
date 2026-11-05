<?php

declare(strict_types=1);

namespace LeviLabs\LaravelOtpiq\Contracts;

use LeviLabs\LaravelOtpiq\Data\Messaging\SendCustomMessageData;
use LeviLabs\LaravelOtpiq\Data\Messaging\SendMessageResponse;
use LeviLabs\LaravelOtpiq\Data\Messaging\SendVerificationData;
use LeviLabs\LaravelOtpiq\Data\Messaging\SendWhatsAppTemplateData;
use LeviLabs\LaravelOtpiq\Data\Messaging\TrackMessageResponse;

interface OtpiqMessagingServiceContract
{
    public function sendVerification(SendVerificationData $data, ?string $project = null): SendMessageResponse;

    public function sendCustom(SendCustomMessageData $data, ?string $project = null): SendMessageResponse;

    public function sendWhatsAppTemplate(SendWhatsAppTemplateData $data, ?string $project = null): SendMessageResponse;

    public function track(string $smsId, ?string $project = null): TrackMessageResponse;
}
