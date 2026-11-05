<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Http;
use LeviLabs\LaravelOtpiq\Facades\OtpiqSenderId;

it('lists sender ids', function (): void {
    Http::fake([
        'https://api.otpiq.com/api/sender-ids' => Http::response([
            'success' => true,
            'data' => [
                [
                    '_id' => '507f1f77bcf86cd799439011',
                    'senderId' => 'OTPIQ',
                    'status' => 'accepted',
                ],
            ],
        ]),
    ]);

    $senderIds = OtpiqSenderId::list();

    expect($senderIds)->toHaveCount(1)
        ->and($senderIds[0]->senderId)->toBe('OTPIQ');
});
