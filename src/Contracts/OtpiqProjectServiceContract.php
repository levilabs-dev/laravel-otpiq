<?php

declare(strict_types=1);

namespace LeviLabs\LaravelOtpiq\Contracts;

use LeviLabs\LaravelOtpiq\Data\Project\ProjectInfo;

interface OtpiqProjectServiceContract
{
    public function info(?string $project = null): ProjectInfo;
}
