<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Http;
use LeviLabs\LaravelOtpiq\Facades\OtpiqWhatsApp;
use LeviLabs\LaravelOtpiq\Facades\OtpiqWhatsAppCampaign;

it('lists whatsapp resources', function (): void {
    Http::fake([
        'https://api.otpiq.com/api/whatsapp/resources' => Http::response([
            'businesses' => [],
        ]),
    ]);

    expect(OtpiqWhatsApp::list())->toBe(['businesses' => []]);
});

it('creates and starts a whatsapp campaign', function (): void {
    Http::fake([
        'https://api.otpiq.com/api/whatsapp/campaigns' => Http::response([
            '_id' => '664abc123def456789012345',
            'status' => 'draft',
        ], 201),
        'https://api.otpiq.com/api/whatsapp/campaigns/664abc123def456789012345/start' => Http::response([
            '_id' => '664abc123def456789012345',
            'status' => 'running',
        ]),
    ]);

    $created = OtpiqWhatsAppCampaign::create([
        'name' => 'Summer promo',
        'templateId' => '664abc123def456789012345',
        'phoneNumberId' => '664def456abc789012345678',
        'scheduleType' => 'now',
    ]);

    expect($created['status'])->toBe('draft');

    $started = OtpiqWhatsAppCampaign::start('664abc123def456789012345');

    expect($started['status'])->toBe('running');
});
