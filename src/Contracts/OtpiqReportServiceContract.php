<?php

declare(strict_types=1);

namespace LeviLabs\LaravelOtpiq\Contracts;

interface OtpiqReportServiceContract
{
    /**
     * @param  array<int, string>|null  $statuses
     * @return array<int, array<string, mixed>>
     */
    public function list(?int $limit = null, ?array $statuses = null, ?string $project = null): array;

    /**
     * @param  array<int, string>|null  $phoneNumbers
     * @param  array<int, string>|null  $statuses
     * @return array<string, mixed>
     */
    public function generate(
        string $startDate,
        string $endDate,
        ?array $phoneNumbers = null,
        ?array $statuses = null,
        ?string $project = null,
    ): array;
}
