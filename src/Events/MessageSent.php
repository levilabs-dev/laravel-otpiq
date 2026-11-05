<?php

declare(strict_types=1);

namespace LeviLabs\LaravelOtpiq\Events;

use LeviLabs\LaravelOtpiq\Data\Messaging\SendMessageResponse;

final class MessageSent
{
    public function __construct(
        public readonly string $project,
        public readonly string $phoneNumber,
        public readonly string $smsType,
        public readonly ?string $provider,
        public readonly SendMessageResponse $response,
    ) {}
}
