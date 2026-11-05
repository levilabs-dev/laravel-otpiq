<?php

declare(strict_types=1);

namespace LeviLabs\LaravelOtpiq\Contracts;

use LeviLabs\LaravelOtpiq\Data\SenderIds\SenderIdData;

interface OtpiqSenderIdServiceContract
{
    /**
     * @return array<int, SenderIdData>
     */
    public function list(?string $project = null): array;
}
