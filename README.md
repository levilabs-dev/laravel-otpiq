# Laravel OTPIQ SDK

<p>
<a href="https://packagist.org/packages/levilabs/laravel-otpiq"><img src="https://img.shields.io/packagist/v/levilabs/laravel-otpiq.svg?style=flat-square&label=Packagist&color=orange" alt="Latest Version on Packagist"></a>
<a href="LICENSE.md"><img src="https://img.shields.io/packagist/l/levilabs/laravel-otpiq.svg?style=flat-square&color=success" alt="License"></a>
</p>

A modern Laravel SDK for [OTPIQ](https://otpiq.com) — **SMS, WhatsApp, and Telegram OTP delivery**, smart fallback routing, delivery webhooks, CSV reports, and **WhatsApp Business campaigns**, with typed DTOs, multi-project config, and optional message persistence.

Maintained by **[Levi Labs](https://levilabs.dev)** — built by [Nizam Omer](https://nizaamomer.com)

**Official API docs:** [docs.otpiq.com](https://docs.otpiq.com) · **Dashboard:** [app.otpiq.com](https://app.otpiq.com)

## Requirements

- PHP 8.2+ (Laravel **13.x** requires PHP **8.3+** on the application)
- Laravel 11.x, 12.x, or 13.x

## Installation

```bash
composer require levilabs/laravel-otpiq
```

```bash
php artisan vendor:publish --tag=otpiq-config
php artisan migrate
```

```env
OTPIQ_API_KEY=sk_live_your_key
OTPIQ_DEFAULT_PROVIDER=whatsapp-telegram-sms
OTPIQ_WEBHOOK_URL=https://your-app.test/webhooks/otpiq/delivery
OTPIQ_WEBHOOK_SECRET=your_webhook_secret
```

Use **`sk_dev…`** keys locally — OTPIQ delivers all test messages to your dashboard dev number ([dev keys guide](https://docs.otpiq.com/useful-articles/how-dev-key-works.md)).

## Send a verification code

```php
use LeviLabs\LaravelOtpiq\Data\Messaging\SendVerificationData;
use LeviLabs\LaravelOtpiq\Enums\Provider;
use LeviLabs\LaravelOtpiq\Facades\OtpiqMessage;

$response = OtpiqMessage::sendVerification(new SendVerificationData(
    phoneNumber: '9647501234567',
    verificationCode: '123456',
    provider: Provider::WhatsAppTelegramSms,
));

// $response->smsId, $response->cost, $response->remainingCredit
```

## Track delivery

```php
$tracking = OtpiqMessage::track($response->smsId);

// $tracking->status, $tracking->isFinalStatus, $tracking->channelFlow
```

If a delivery webhook is missed, run:

```bash
php artisan otpiq:sync-statuses
```

## Delivery webhooks

OTPIQ signs delivery reports with `X-OTPIQ-Webhook-Secret` ([docs](https://docs.otpiq.com/essentials/how-webhook-works.md)).

```php
use LeviLabs\LaravelOtpiq\Support\DeliveryWebhook;

$report = DeliveryWebhook::dispatchIfVerified(
    payload: $request->all(),
    receivedSecret: $request->header('X-OTPIQ-Webhook-Secret'),
    expectedSecret: config('otpiq.delivery_report.webhook_secret'),
);

if ($report === null) {
    abort(401);
}

return response('OK');
```

## WhatsApp inbound webhooks

Configured per phone number in the OTPIQ dashboard. Verify HMAC signatures with `WhatsAppInboundWebhookVerifier` ([docs](https://docs.otpiq.com/whatsapp-business-api/whatsapp-webhook.md)).

## Facades & services

| Facade | Purpose |
| --- | --- |
| `OtpiqProject` | Project info & credit (`GET /info`) |
| `OtpiqMessage` | Send & track messages |
| `OtpiqSenderId` | List sender IDs |
| `OtpiqPricing` | International SMS pricing |
| `OtpiqReport` | Delivery report CSV list & generate |
| `OtpiqWhatsApp` | Connected WABA resources |
| `OtpiqWhatsAppCampaign` | Campaign CRUD, recipients, start, sample |

## Multi-project

Add entries under `projects` in `config/otpiq.php` and pass the project key as the last argument on any call.

## Persistence

Successful sends upsert into `otpiq_messages` via the `MessageSent` event. Webhooks and `track()` update status through `DeliveryStatusUpdated`.

## Testing

```bash
composer test
composer analyse
```

## Security

See [SECURITY.md](SECURITY.md). Never expose API keys or webhook secrets in client-side code or public repositories.

## License

MIT — see [LICENSE.md](LICENSE.md).

OTPIQ is a commercial messaging service; billing and refunds follow [OTPIQ’s terms](https://otpiq.com/terms-of-use).
