<?php

declare(strict_types=1);

namespace LeviLabs\LaravelOtpiq\Data\Project;

final readonly class ProjectInfo
{
    public function __construct(
        public string $projectName,
        public float $credit,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            projectName: (string) ($data['projectName'] ?? ''),
            credit: (float) ($data['credit'] ?? 0),
        );
    }
}
