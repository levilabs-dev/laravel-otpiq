<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Http;
use LeviLabs\LaravelOtpiq\Facades\OtpiqProject;

it('fetches project info', function (): void {
    Http::fake([
        'https://api.otpiq.com/api/info' => Http::response([
            'projectName' => 'My SMS Project',
            'credit' => 15000,
        ]),
    ]);

    $info = OtpiqProject::info();

    expect($info->projectName)->toBe('My SMS Project')
        ->and($info->credit)->toBe(15000.0);
});
