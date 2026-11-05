<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Http;
use LeviLabs\LaravelOtpiq\Facades\OtpiqPricing;
use LeviLabs\LaravelOtpiq\Facades\OtpiqReport;

it('lists international sms pricing', function (): void {
    Http::fake([
        'https://api.otpiq.com/api/international-sms-pricing*' => Http::response([
            'currency' => 'IQD',
            'page' => 1,
            'limit' => 100,
            'total' => 0,
            'pages' => 1,
            'data' => [],
        ]),
    ]);

    $pricing = OtpiqPricing::internationalSmsPricing(page: 1, limit: 100);

    expect($pricing['currency'])->toBe('IQD');
});

it('generates delivery reports', function (): void {
    Http::fake([
        'https://api.otpiq.com/api/reports/generate' => Http::response([
            '_id' => '65a1b2c3d4e5f6789012345',
            'reportId' => '65a1b2c3d4e5f6789012345',
            'downloadUrl' => 'https://cdn.example.com/report.csv',
            'recordCount' => 10,
            'dateRange' => [
                'startDate' => '2026-01-01T00:00:00.000Z',
                'endDate' => '2026-01-31T23:59:59.999Z',
            ],
            'expiresAt' => '2026-03-12T12:00:00.000Z',
            'status' => 'available',
            'message' => 'Report generated',
        ], 201),
    ]);

    $report = OtpiqReport::generate(
        '2026-01-01T00:00:00.000Z',
        '2026-01-31T23:59:59.999Z',
    );

    expect($report['downloadUrl'])->toContain('report.csv');
});
