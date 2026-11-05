<?php

declare(strict_types=1);

return [

    /*
    |--------------------------------------------------------------------------
    | Default OTPIQ Project
    |--------------------------------------------------------------------------
    |
    | The project used when no explicit project is requested. Matches a key
    | in the "projects" array below.
    |
    */
    'default' => env('OTPIQ_PROJECT', 'default'),

    /*
    |--------------------------------------------------------------------------
    | OTPIQ Projects
    |--------------------------------------------------------------------------
    |
    | Each project holds its own API key so one Laravel app can send messages
    | for multiple OTPIQ dashboard projects. Use sk_live… in production and
    | sk_dev… locally (dev keys always deliver to your configured dev number).
    |
    */
    'projects' => [
        'default' => [
            'api_key' => env('OTPIQ_API_KEY'),
            'base_url' => env('OTPIQ_BASE_URL', 'https://api.otpiq.com/api'),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Send Defaults
    |--------------------------------------------------------------------------
    |
    | provider: default routing when a send request omits provider. See
    | docs.otpiq.com for whatsapp-sms, whatsapp-telegram-sms, sms, etc.
    |
    */
    'defaults' => [
        'provider' => env('OTPIQ_DEFAULT_PROVIDER', 'whatsapp-telegram-sms'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Delivery report webhooks (optional)
    |--------------------------------------------------------------------------
    |
    | When set, the SDK merges this deliveryReport block into outbound sends
    | unless the request DTO already defines deliveryReport.
    |
    */
    'delivery_report' => [
        'webhook_url' => env('OTPIQ_WEBHOOK_URL'),
        'webhook_secret' => env('OTPIQ_WEBHOOK_SECRET'),
        'delivery_report_type' => env('OTPIQ_WEBHOOK_REPORT_TYPE', 'final'),
    ],

    /*
    |--------------------------------------------------------------------------
    | HTTP Client
    |--------------------------------------------------------------------------
    */
    'http' => [
        'timeout' => (int) env('OTPIQ_HTTP_TIMEOUT', 15),
        'retry_times' => (int) env('OTPIQ_HTTP_RETRY_TIMES', 1),
        'retry_sleep_ms' => (int) env('OTPIQ_HTTP_RETRY_SLEEP_MS', 200),
    ],

];
