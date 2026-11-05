<?php

declare(strict_types=1);

namespace LeviLabs\LaravelOtpiq\Services;

use Illuminate\Support\Facades\Event;
use LeviLabs\LaravelOtpiq\Contracts\OtpiqMessagingServiceContract;
use LeviLabs\LaravelOtpiq\Data\Messaging\DeliveryReportConfig;
use LeviLabs\LaravelOtpiq\Data\Messaging\SendCustomMessageData;
use LeviLabs\LaravelOtpiq\Data\Messaging\SendMessageResponse;
use LeviLabs\LaravelOtpiq\Data\Messaging\SendVerificationData;
use LeviLabs\LaravelOtpiq\Data\Messaging\SendWhatsAppTemplateData;
use LeviLabs\LaravelOtpiq\Data\Messaging\TrackMessageResponse;
use LeviLabs\LaravelOtpiq\Events\DeliveryStatusUpdated;
use LeviLabs\LaravelOtpiq\Events\MessageSent;
use LeviLabs\LaravelOtpiq\Services\Concerns\TalksToOtpiq;

final class OtpiqMessagingService implements OtpiqMessagingServiceContract
{
    use TalksToOtpiq;

    public function sendVerification(SendVerificationData $data, ?string $project = null): SendMessageResponse
    {
        return $this->sendMessage($data->toPayload(DeliveryReportConfig::fromConfig()), $project);
    }

    public function sendCustom(SendCustomMessageData $data, ?string $project = null): SendMessageResponse
    {
        return $this->sendMessage($data->toPayload(DeliveryReportConfig::fromConfig()), $project);
    }

    public function sendWhatsAppTemplate(SendWhatsAppTemplateData $data, ?string $project = null): SendMessageResponse
    {
        return $this->sendMessage($data->toPayload(DeliveryReportConfig::fromConfig()), $project);
    }

    public function track(string $smsId, ?string $project = null): TrackMessageResponse
    {
        $resolvedProject = $this->resolvedProject($project);
        $data = $this->send('track SMS', 'get', '/sms/track/'.rawurlencode($smsId), project: $project);
        $response = TrackMessageResponse::fromArray($data);

        Event::dispatch(new DeliveryStatusUpdated(
            project: $resolvedProject,
            tracking: $response,
        ));

        return $response;
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    private function sendMessage(array $payload, ?string $project): SendMessageResponse
    {
        $resolvedProject = $this->resolvedProject($project);
        $data = $this->send('send SMS', 'post', '/sms', $payload, $project);
        $response = SendMessageResponse::fromArray($data);

        Event::dispatch(new MessageSent(
            project: $resolvedProject,
            phoneNumber: (string) ($payload['phoneNumber'] ?? ''),
            smsType: (string) ($payload['smsType'] ?? ''),
            provider: isset($payload['provider']) ? (string) $payload['provider'] : null,
            response: $response,
        ));

        return $response;
    }
}
