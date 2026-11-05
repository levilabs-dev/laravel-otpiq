<?php

declare(strict_types=1);

namespace LeviLabs\LaravelOtpiq\Services;

use LeviLabs\LaravelOtpiq\Contracts\OtpiqSenderIdServiceContract;
use LeviLabs\LaravelOtpiq\Data\SenderIds\SenderIdData;
use LeviLabs\LaravelOtpiq\Services\Concerns\TalksToOtpiq;

final class OtpiqSenderIdService implements OtpiqSenderIdServiceContract
{
    use TalksToOtpiq;

    public function list(?string $project = null): array
    {
        $data = $this->send('list sender IDs', 'get', '/sender-ids', project: $project);

        /** @var array<int, array<string, mixed>> $items */
        $items = is_array($data['data'] ?? null) ? $data['data'] : [];

        return array_map(
            fn (array $item): SenderIdData => SenderIdData::fromArray($item),
            $items,
        );
    }
}
