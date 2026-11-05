<?php

declare(strict_types=1);

namespace LeviLabs\LaravelOtpiq\Services;

use LeviLabs\LaravelOtpiq\Contracts\OtpiqProjectServiceContract;
use LeviLabs\LaravelOtpiq\Data\Project\ProjectInfo;
use LeviLabs\LaravelOtpiq\Services\Concerns\TalksToOtpiq;

final class OtpiqProjectService implements OtpiqProjectServiceContract
{
    use TalksToOtpiq;

    public function info(?string $project = null): ProjectInfo
    {
        $data = $this->send('project info', 'get', '/info', project: $project);

        return ProjectInfo::fromArray($data);
    }
}
