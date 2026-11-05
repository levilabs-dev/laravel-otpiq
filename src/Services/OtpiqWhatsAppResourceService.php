<?php

declare(strict_types=1);

namespace LeviLabs\LaravelOtpiq\Services;

use LeviLabs\LaravelOtpiq\Contracts\OtpiqWhatsAppResourceServiceContract;
use LeviLabs\LaravelOtpiq\Services\Concerns\TalksToOtpiq;

final class OtpiqWhatsAppResourceService implements OtpiqWhatsAppResourceServiceContract
{
    use TalksToOtpiq;

    public function list(?string $project = null): array
    {
        return $this->send('list WhatsApp resources', 'get', '/whatsapp/resources', project: $project);
    }
}
