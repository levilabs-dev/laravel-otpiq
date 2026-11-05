<?php

declare(strict_types=1);

return [

    /*
    |--------------------------------------------------------------------------
    | Default OTPIQ Project
    |--------------------------------------------------------------------------
    |
    | The project key used when you omit the $project argument on SDK calls.
    | Must match a key in the "projects" array below (e.g. "default", "staging").
    |
    | .env: OTPIQ_PROJECT=default
    |
    */
    'default' => env('OTPIQ_PROJECT', 'default'),

    /*
    |--------------------------------------------------------------------------
    | OTPIQ Projects
    |--------------------------------------------------------------------------
    |
    | One Laravel app can talk to several OTPIQ dashboard projects (e.g. prod
    | vs staging, or separate client apps). Each entry needs its own API key.
    |
    | api_key: from app.otpiq.com → Project → API Keys
    |   - sk_live…  production (messages go to the real phoneNumber you send)
    |   - sk_dev…   development (OTPIQ always delivers to your dev number;
    |               the phoneNumber in the API body is ignored)
    |
    | base_url: leave the default unless OTPIQ gives you another host.
    |
    | Example second project (uncomment and wire .env):
    |
    |   'staging' => [
    |       'api_key' => env('OTPIQ_STAGING_API_KEY'),
    |       'base_url' => env('OTPIQ_BASE_URL', 'https://api.otpiq.com/api'),
    |   ],
    |
    | Usage: OtpiqMessage::sendVerification($data, project: 'staging');
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
    | Send defaults
    |--------------------------------------------------------------------------
    |
    | provider — used when your SendVerificationData / SendCustomMessageData /
    | SendWhatsAppTemplateData does not set Provider explicitly.
    |
    | .env: OTPIQ_DEFAULT_PROVIDER=whatsapp-telegram-sms
    |
    | Allowed values (OTPIQ API "provider" field):
    |
    |   sms                     SMS only (carrier).
    |   whatsapp                WhatsApp only.
    |   telegram                Telegram only.
    |   whatsapp-sms            Try WhatsApp, then fall back to SMS.
    |   telegram-sms            Try Telegram, then fall back to SMS.
    |   whatsapp-telegram-sms   Try WhatsApp → Telegram → SMS (max delivery;
    |                           recommended default for OTP in Iraq).
    |
    | You can override per message with Provider::… on the send DTO, or by
    | passing provider in the payload when using lower-level APIs.
    |
    | Docs: https://docs.otpiq.com/api-reference/messaging/post
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
    | When webhook_url is set, the SDK merges a deliveryReport object into
    | outbound sends unless your DTO already includes DeliveryReportConfig.
    |
    | webhook_url:     HTTPS endpoint on your app (must be publicly reachable).
    | webhook_secret:  sent back as X-OTPIQ-Webhook-Secret; verify before trust.
    | delivery_report_type:
    |   - final  webhooks only for delivered / failed (recommended).
    |   - all    every status (sent, delivered, failed, …).
    |
    | .env:
    |   OTPIQ_WEBHOOK_URL=https://your-app.com/webhooks/otpiq/delivery
    |   OTPIQ_WEBHOOK_SECRET=your_secret
    |   OTPIQ_WEBHOOK_REPORT_TYPE=final
    |
    | Docs: https://docs.otpiq.com/essentials/how-webhook-works
    |
    */
    'delivery_report' => [
        'webhook_url' => env('OTPIQ_WEBHOOK_URL'),
        'webhook_secret' => env('OTPIQ_WEBHOOK_SECRET'),
        'delivery_report_type' => env('OTPIQ_WEBHOOK_REPORT_TYPE', 'final'),
    ],

    /*
    |--------------------------------------------------------------------------
    | HTTP client
    |--------------------------------------------------------------------------
    |
    | .env: OTPIQ_HTTP_TIMEOUT, OTPIQ_HTTP_RETRY_TIMES, OTPIQ_HTTP_RETRY_SLEEP_MS
    |
    */
    'http' => [
        'timeout' => (int) env('OTPIQ_HTTP_TIMEOUT', 15),
        'retry_times' => (int) env('OTPIQ_HTTP_RETRY_TIMES', 1),
        'retry_sleep_ms' => (int) env('OTPIQ_HTTP_RETRY_SLEEP_MS', 200),
    ],

];
