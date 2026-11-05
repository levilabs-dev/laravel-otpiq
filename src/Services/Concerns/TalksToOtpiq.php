<?php

declare(strict_types=1);

namespace LeviLabs\LaravelOtpiq\Services\Concerns;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;
use LeviLabs\LaravelOtpiq\Exceptions\OtpiqException;
use LeviLabs\LaravelOtpiq\Exceptions\OtpiqProjectException;

trait TalksToOtpiq
{
    /**
     * @return array{api_key: string, base_url: string}
     */
    private function projectConfig(?string $project = null): array
    {
        $project = $project ?? (string) config('otpiq.default', 'default');
        $config = config("otpiq.projects.{$project}");

        if (! is_array($config)) {
            throw OtpiqProjectException::unknownProject($project);
        }

        $apiKey = isset($config['api_key']) ? (string) $config['api_key'] : '';

        if ($apiKey === '') {
            throw OtpiqProjectException::missingApiKey($project);
        }

        return [
            'api_key' => $apiKey,
            'base_url' => rtrim((string) ($config['base_url'] ?? 'https://api.otpiq.com/api'), '/'),
        ];
    }

    private function client(?string $project = null): PendingRequest
    {
        $config = $this->projectConfig($project);

        return Http::baseUrl($config['base_url'])
            ->withToken($config['api_key'])
            ->timeout((int) config('otpiq.http.timeout', 15))
            ->retry(
                (int) config('otpiq.http.retry_times', 1),
                (int) config('otpiq.http.retry_sleep_ms', 200),
            )
            ->acceptJson()
            ->asJson();
    }

    /**
     * @param  array<string, mixed>|null  $payload
     * @return array<string, mixed>
     */
    private function send(string $action, string $method, string $path, ?array $payload = null, ?string $project = null): array
    {
        $request = $this->client($project);
        $response = match ($method) {
            'post' => $request->post($path, $payload ?? []),
            'patch' => $request->patch($path, $payload ?? []),
            'delete' => $request->delete($path),
            default => $request->get($path, $payload ?? []),
        };

        if ($response->failed()) {
            $message = $response->json('message') ?? $response->json('error');
            $data = $response->json();

            throw OtpiqException::requestFailed(
                $action,
                $response->status(),
                is_string($message) ? $message : null,
                is_array($data) ? $data : null,
            );
        }

        /** @var array<string, mixed> $json */
        $json = $response->json() ?? [];

        return $json;
    }

    protected function resolvedProject(?string $project): string
    {
        return $project ?? (string) config('otpiq.default', 'default');
    }
}
