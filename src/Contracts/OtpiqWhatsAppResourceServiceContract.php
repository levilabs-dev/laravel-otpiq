<?php

declare(strict_types=1);

namespace LeviLabs\LaravelOtpiq\Contracts;

interface OtpiqWhatsAppResourceServiceContract
{
    /**
     * @return array<string, mixed>
     */
    public function list(?string $project = null): array;
}
