<?php

declare(strict_types=1);

namespace LeviLabs\LaravelOtpiq\Services;

use LeviLabs\LaravelOtpiq\Contracts\OtpiqReportServiceContract;
use LeviLabs\LaravelOtpiq\Services\Concerns\TalksToOtpiq;

final class OtpiqReportService implements OtpiqReportServiceContract
{
    use TalksToOtpiq;

    public function list(?int $limit = null, ?array $statuses = null, ?string $project = null): array
    {
        $query = array_filter([
            'limit' => $limit,
            'status' => $statuses !== null ? implode(',', $statuses) : null,
        ], fn (mixed $value): bool => $value !== null);

        /** @var array<int, array<string, mixed>> $data */
        $data = $this->send('list delivery reports', 'get', '/reports', $query, $project);

        return $data;
    }

    public function generate(
        string $startDate,
        string $endDate,
        ?array $phoneNumbers = null,
        ?array $statuses = null,
        ?string $project = null,
    ): array {
        $payload = array_filter([
            'startDate' => $startDate,
            'endDate' => $endDate,
            'phoneNumbers' => $phoneNumbers,
            'statuses' => $statuses,
        ], fn (mixed $value): bool => $value !== null);

        return $this->send('generate delivery report', 'post', '/reports/generate', $payload, $project);
    }
}
