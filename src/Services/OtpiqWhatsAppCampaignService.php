<?php

declare(strict_types=1);

namespace LeviLabs\LaravelOtpiq\Services;

use LeviLabs\LaravelOtpiq\Contracts\OtpiqWhatsAppCampaignServiceContract;
use LeviLabs\LaravelOtpiq\Services\Concerns\TalksToOtpiq;

final class OtpiqWhatsAppCampaignService implements OtpiqWhatsAppCampaignServiceContract
{
    use TalksToOtpiq;

    public function recipientPayload(
        ?string $templateId = null,
        ?string $campaignId = null,
        ?string $project = null,
    ): array {
        $query = array_filter([
            'templateId' => $templateId,
            '_id' => $campaignId,
            'campaignId' => $campaignId,
        ], fn (mixed $value): bool => $value !== null);

        return $this->send('campaign recipient payload', 'get', '/whatsapp/campaigns/recipient-payload', $query, $project);
    }

    public function list(
        ?string $status = null,
        ?int $page = null,
        ?int $limit = null,
        ?string $project = null,
    ): array {
        $query = array_filter([
            'status' => $status,
            'page' => $page,
            'limit' => $limit,
        ], fn (mixed $value): bool => $value !== null);

        return $this->send('list WhatsApp campaigns', 'get', '/whatsapp/campaigns', $query, $project);
    }

    public function create(array $payload, ?string $project = null): array
    {
        return $this->send('create WhatsApp campaign', 'post', '/whatsapp/campaigns', $payload, $project);
    }

    public function get(
        string $campaignId,
        ?int $page = null,
        ?int $limit = null,
        ?string $recipientStatus = null,
        ?string $phoneNumber = null,
        ?string $project = null,
    ): array {
        $query = array_filter([
            'page' => $page,
            'limit' => $limit,
            'status' => $recipientStatus,
            'phoneNumber' => $phoneNumber,
        ], fn (mixed $value): bool => $value !== null);

        return $this->send('get WhatsApp campaign', 'get', '/whatsapp/campaigns/'.rawurlencode($campaignId), $query, $project);
    }

    public function update(string $campaignId, array $payload, ?string $project = null): array
    {
        return $this->send('update WhatsApp campaign', 'patch', '/whatsapp/campaigns/'.rawurlencode($campaignId), $payload, $project);
    }

    public function delete(string $campaignId, ?string $project = null): array
    {
        return $this->send('delete WhatsApp campaign', 'delete', '/whatsapp/campaigns/'.rawurlencode($campaignId), project: $project);
    }

    public function attachRecipients(string $campaignId, array $payload, ?string $project = null): array
    {
        return $this->send(
            'attach WhatsApp campaign recipients',
            'post',
            '/whatsapp/campaigns/'.rawurlencode($campaignId).'/recipients',
            $payload,
            $project,
        );
    }

    public function start(string $campaignId, ?string $project = null): array
    {
        return $this->send(
            'start WhatsApp campaign',
            'post',
            '/whatsapp/campaigns/'.rawurlencode($campaignId).'/start',
            [],
            $project,
        );
    }

    public function sendSample(string $campaignId, array $payload, ?string $project = null): array
    {
        return $this->send(
            'send WhatsApp campaign sample',
            'post',
            '/whatsapp/campaigns/'.rawurlencode($campaignId).'/sample',
            $payload,
            $project,
        );
    }
}
