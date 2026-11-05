<?php

declare(strict_types=1);

namespace LeviLabs\LaravelOtpiq\Contracts;

interface OtpiqWhatsAppCampaignServiceContract
{
    /**
     * @return array<string, mixed>
     */
    public function recipientPayload(
        ?string $templateId = null,
        ?string $campaignId = null,
        ?string $project = null,
    ): array;

    /**
     * @return array<string, mixed>
     */
    public function list(
        ?string $status = null,
        ?int $page = null,
        ?int $limit = null,
        ?string $project = null,
    ): array;

    /**
     * @param  array<string, mixed>  $payload
     * @return array<string, mixed>
     */
    public function create(array $payload, ?string $project = null): array;

    /**
     * @return array<string, mixed>
     */
    public function get(
        string $campaignId,
        ?int $page = null,
        ?int $limit = null,
        ?string $recipientStatus = null,
        ?string $phoneNumber = null,
        ?string $project = null,
    ): array;

    /**
     * @param  array<string, mixed>  $payload
     * @return array<string, mixed>
     */
    public function update(string $campaignId, array $payload, ?string $project = null): array;

    /**
     * @return array<string, mixed>
     */
    public function delete(string $campaignId, ?string $project = null): array;

    /**
     * @param  array<string, mixed>  $payload
     * @return array<string, mixed>
     */
    public function attachRecipients(string $campaignId, array $payload, ?string $project = null): array;

    /**
     * @return array<string, mixed>
     */
    public function start(string $campaignId, ?string $project = null): array;

    /**
     * @param  array<string, mixed>  $payload
     * @return array<string, mixed>
     */
    public function sendSample(string $campaignId, array $payload, ?string $project = null): array;
}
