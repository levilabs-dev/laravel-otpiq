<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Http;
use LeviLabs\LaravelOtpiq\Models\OtpiqMessage;

it('syncs non-final messages from the track api', function (): void {
    OtpiqMessage::query()->create([
        'project' => 'default',
        'sms_id' => 'sms-1234567890abcdef123456',
        'phone_number' => '964750123456',
        'sms_type' => 'verification',
        'status' => 'pending',
        'is_final' => false,
    ]);

    Http::fake([
        'https://api.otpiq.com/api/sms/track/*' => Http::response([
            'smsId' => 'sms-1234567890abcdef123456',
            'phoneNumber' => '964750123456',
            'status' => 'delivered',
            'cost' => 200,
            'isFinalStatus' => true,
            'lastChannel' => 'sms',
            'channelFlow' => [],
        ]),
    ]);

    $this->artisan('otpiq:sync-statuses', ['--limit' => 10])
        ->assertSuccessful();

    expect(OtpiqMessage::query()->first()?->status)->toBe('delivered')
        ->and(OtpiqMessage::query()->first()?->is_final)->toBeTrue();
});
