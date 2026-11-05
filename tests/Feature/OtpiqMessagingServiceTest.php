<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Http;
use LeviLabs\LaravelOtpiq\Data\Messaging\SendVerificationData;
use LeviLabs\LaravelOtpiq\Enums\Provider;
use LeviLabs\LaravelOtpiq\Facades\OtpiqMessage;
use LeviLabs\LaravelOtpiq\Models\OtpiqMessage as OtpiqMessageModel;

it('sends verification messages and persists them', function (): void {
    Http::fake([
        'https://api.otpiq.com/api/sms' => Http::response([
            'message' => 'SMS task created successfully',
            'smsId' => 'sms-1234567890abcdef123456',
            'remainingCredit' => 14800,
            'cost' => 200,
            'canCover' => true,
            'paymentType' => 'prepaid',
        ]),
    ]);

    $response = OtpiqMessage::sendVerification(new SendVerificationData(
        phoneNumber: '964750123456',
        verificationCode: '123456',
        provider: Provider::WhatsAppTelegramSms,
    ));

    expect($response->smsId)->toBe('sms-1234567890abcdef123456');

    Http::assertSent(function ($request): bool {
        return $request->url() === 'https://api.otpiq.com/api/sms'
            && $request['smsType'] === 'verification'
            && $request['phoneNumber'] === '964750123456';
    });

    expect(OtpiqMessageModel::query()->where('sms_id', $response->smsId)->exists())->toBeTrue();
});

it('tracks message delivery status', function (): void {
    Http::fake([
        'https://api.otpiq.com/api/sms/track/*' => Http::response([
            'smsId' => 'sms-1234567890abcdef123456',
            'phoneNumber' => '964750123456',
            'status' => 'delivered',
            'cost' => 200,
            'isFinalStatus' => true,
            'lastChannel' => 'whatsapp',
            'channelFlow' => [
                ['channel' => 'whatsapp', 'tried' => true, 'success' => true],
            ],
        ]),
    ]);

    $tracking = OtpiqMessage::track('sms-1234567890abcdef123456');

    expect($tracking->status->value)->toBe('delivered')
        ->and($tracking->isFinalStatus)->toBeTrue();
});
