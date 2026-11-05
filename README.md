# Laravel OTPIQ SDK

<p>
<a href="https://packagist.org/packages/levilabs/laravel-otpiq"><img src="https://img.shields.io/packagist/v/levilabs/laravel-otpiq.svg?style=flat-square&label=Packagist&color=orange" alt="Latest Version on Packagist"></a>
<a href="LICENSE.md"><img src="https://img.shields.io/packagist/l/levilabs/laravel-otpiq.svg?style=flat-square&color=success" alt="License"></a>
</p>

A modern Laravel SDK for [OTPIQ](https://otpiq.com) — **SMS, WhatsApp, and Telegram OTP delivery**, smart fallback routing, delivery webhooks, CSV reports, and **WhatsApp Business campaigns**, with typed DTOs, multi-project config, and optional message persistence.

Maintained by **[Levi Labs](https://levilabs.dev)** — built by [Nizam Omer](https://nizaamomer.com)

**Official API docs:** [docs.otpiq.com](https://docs.otpiq.com) · **Dashboard:** [app.otpiq.com](https://app.otpiq.com)

## Table of contents

- [Requirements](#requirements)
- [Installation](#installation)
- [Configuration](#configuration)
- [Provider routing (`OTPIQ_DEFAULT_PROVIDER`)](#provider-routing-otpiq_default_provider)
- [Multiple OTPIQ projects](#multiple-otpiq-projects)
- [Send messages](#send-messages)
- [Track delivery](#track-delivery)
- [Delivery webhooks](#delivery-webhooks)
- [WhatsApp inbound webhooks](#whatsapp-inbound-webhooks)
- [Facades & services](#facades--services)
- [Persistence & sync](#persistence--sync)
- [Testing](#testing)
- [Security](#security)
- [License](#license)

## Requirements

- PHP 8.2+ (Laravel **13.x** requires PHP **8.3+** on the application)
- Laravel 11.x, 12.x, or 13.x
- An OTPIQ project API key from [app.otpiq.com](https://app.otpiq.com)

## Installation

```bash
composer require levilabs/laravel-otpiq
php artisan vendor:publish --tag=otpiq-config
php artisan migrate
```

## Configuration

Publish copies [`config/otpiq.php`](config/otpiq.php). Set credentials in `.env`:

```env
# Which entry in config('otpiq.projects') to use by default
OTPIQ_PROJECT=default

# Primary project (maps to projects.default in config)
OTPIQ_API_KEY=sk_live_your_key_here
OTPIQ_BASE_URL=https://api.otpiq.com/api

# Default channel routing when a send DTO omits provider (see table below)
OTPIQ_DEFAULT_PROVIDER=whatsapp-telegram-sms

# Optional: merged into every send unless you pass DeliveryReportConfig on the DTO
OTPIQ_WEBHOOK_URL=https://your-app.com/webhooks/otpiq/delivery
OTPIQ_WEBHOOK_SECRET=your_webhook_secret
OTPIQ_WEBHOOK_REPORT_TYPE=final

# Optional HTTP tuning
OTPIQ_HTTP_TIMEOUT=15
OTPIQ_HTTP_RETRY_TIMES=1
OTPIQ_HTTP_RETRY_SLEEP_MS=200
```

### API keys

| Key prefix | Use |
| --- | --- |
| `sk_live…` | Production — messages are sent to the `phoneNumber` you pass in the API. |
| `sk_dev…` | Development — OTPIQ **always** delivers to the dev phone number you set in the dashboard; the number in your code is ignored. Never deploy dev keys. |

Guide: [How dev keys work](https://docs.otpiq.com/useful-articles/how-dev-key-works.md)

### Phone numbers

OTPIQ expects **digits only**, usually Iraq format without `+` or spaces (e.g. `9647501234567`). The SDK normalizes via `PhoneNumber::normalize()` when you use the send DTOs.

## Provider routing (`OTPIQ_DEFAULT_PROVIDER`)

The `provider` field tells OTPIQ **which channels to try** and in **what order**. If you omit `provider` on `SendVerificationData` / `SendCustomMessageData`, the SDK uses `config('otpiq.defaults.provider')`, which comes from `OTPIQ_DEFAULT_PROVIDER`.

| Value | Behavior |
| --- | --- |
| `sms` | SMS only (carrier). |
| `whatsapp` | WhatsApp only. |
| `telegram` | Telegram only. |
| `whatsapp-sms` | WhatsApp first; if that fails, SMS. |
| `telegram-sms` | Telegram first; if that fails, SMS. |
| `whatsapp-telegram-sms` | WhatsApp → Telegram → SMS (widest reach; **default** in config). |

Override per message with the `Provider` enum:

```php
use LeviLabs\LaravelOtpiq\Enums\Provider;
use LeviLabs\LaravelOtpiq\Data\Messaging\SendVerificationData;

OtpiqMessage::sendVerification(new SendVerificationData(
    phoneNumber: '9647501234567',
    verificationCode: '123456',
    provider: Provider::WhatsAppSms, // ignores OTPIQ_DEFAULT_PROVIDER for this send
));
```

Reference: [Send SMS API](https://docs.otpiq.com/api-reference/messaging/post)

## Multiple OTPIQ projects

One Laravel application can send on behalf of **several OTPIQ dashboard projects** (production vs staging, white-label apps, etc.). Each project has its own API key and credit balance.

**1. Add keys in `config/otpiq.php`:**

```php
'projects' => [
    'default' => [
        'api_key' => env('OTPIQ_API_KEY'),
        'base_url' => env('OTPIQ_BASE_URL', 'https://api.otpiq.com/api'),
    ],
    'staging' => [
        'api_key' => env('OTPIQ_STAGING_API_KEY'),
        'base_url' => env('OTPIQ_BASE_URL', 'https://api.otpiq.com/api'),
    ],
],
```

**2. `.env`:**

```env
OTPIQ_PROJECT=default
OTPIQ_API_KEY=sk_live_prod_…
OTPIQ_STAGING_API_KEY=sk_dev_…
```

**3. Pass the project key on any call:**

```php
OtpiqMessage::sendVerification($data, project: 'staging');
OtpiqMessage::track($smsId, project: 'staging');
OtpiqProject::info(project: 'default');
```

If the project key is missing or has no `api_key`, the SDK throws `OtpiqProjectException`.

## Send messages

### Verification OTP (most common)

```php
use LeviLabs\LaravelOtpiq\Data\Messaging\SendVerificationData;
use LeviLabs\LaravelOtpiq\Facades\OtpiqMessage;

$response = OtpiqMessage::sendVerification(new SendVerificationData(
    phoneNumber: '9647501234567',
    verificationCode: '123456',
    // provider: optional — defaults to OTPIQ_DEFAULT_PROVIDER
    // senderId: optional — custom SMS sender ID when supported
));

// $response->smsId, $response->cost, $response->remainingCredit, $response->paymentType
```

### Custom SMS / transactional text

Requires appropriate sender ID and OTPIQ product rules for non-OTP SMS:

```php
use LeviLabs\LaravelOtpiq\Data\Messaging\SendCustomMessageData;

OtpiqMessage::sendCustom(new SendCustomMessageData(
    phoneNumber: '9647501234567',
    customMessage: 'Your order #1042 is confirmed.',
    senderId: 'MyBrand',
));
```

### WhatsApp template message

```php
use LeviLabs\LaravelOtpiq\Data\Messaging\SendWhatsAppTemplateData;
use LeviLabs\LaravelOtpiq\Enums\Provider;

OtpiqMessage::sendWhatsAppTemplate(new SendWhatsAppTemplateData(
    phoneNumber: '9647501234567',
    templateName: 'verification_template',
    whatsappAccountId: '68c46fecc509cdcec8fb3ef2',
    whatsappPhoneId: '68da31fb518ac3db3eb0a0f4',
    templateParameters: ['body' => ['1' => '123456']],
    provider: Provider::WhatsApp,
));
```

List connected WhatsApp assets with `OtpiqWhatsApp::list()`.

## Track delivery

Poll status when you do not rely solely on webhooks:

```php
$tracking = OtpiqMessage::track($response->smsId);

// $tracking->status (pending, sent, delivered, read, failed)
// $tracking->isFinalStatus, $tracking->lastChannel, $tracking->channelFlow
```

## Delivery webhooks

Configure `OTPIQ_WEBHOOK_*` or pass `DeliveryReportConfig` on a single send. OTPIQ POSTs JSON to your URL and sends `X-OTPIQ-Webhook-Secret` ([docs](https://docs.otpiq.com/essentials/how-webhook-works.md)).

`OTPIQ_WEBHOOK_REPORT_TYPE`:

| Value | Meaning |
| --- | --- |
| `final` | Webhooks only when the message reaches a terminal state (delivered or failed). |
| `all` | Webhooks for intermediate states too (e.g. `sent`). |

Example route handler:

```php
use LeviLabs\LaravelOtpiq\Support\DeliveryWebhook;

$report = DeliveryWebhook::dispatchIfVerified(
    payload: $request->all(),
    receivedSecret: $request->header('X-OTPIQ-Webhook-Secret'),
    expectedSecret: config('otpiq.delivery_report.webhook_secret'),
    project: config('otpiq.default'),
);

if ($report === null) {
    abort(401);
}

return response('OK');
```

Respond with **2xx within ~10 seconds**. Use `$report->smsId` for idempotency.

## WhatsApp inbound webhooks

Separate from SMS **delivery** webhooks. Configure per phone number in the OTPIQ dashboard. Verify with HMAC (`X-OTPIQ-Webhook-Signature`, `X-OTPIQ-Webhook-Timestamp`) using `WhatsAppInboundWebhookVerifier` — [inbound webhook docs](https://docs.otpiq.com/whatsapp-business-api/whatsapp-webhook.md).

## Facades & services

| Facade | Purpose |
| --- | --- |
| `OtpiqProject` | Project info & credit (`GET /info`) |
| `OtpiqMessage` | Send (verification / custom / WhatsApp template) & track |
| `OtpiqSenderId` | List registered sender IDs |
| `OtpiqPricing` | International SMS pricing (paginated) |
| `OtpiqReport` | List & generate CSV delivery reports |
| `OtpiqWhatsApp` | Connected businesses, numbers, templates |
| `OtpiqWhatsAppCampaign` | Create campaigns, attach recipients, start, sample send |

Resolve contracts from the container if you prefer constructor injection (e.g. `OtpiqMessagingServiceContract`).

## Persistence & sync

- **`otpiq_messages`** — upserted on send (`MessageSent`) and on webhook / track updates (`DeliveryStatusUpdated`).
- **`php artisan otpiq:sync-statuses`** — re-calls the track API for rows where `is_final` is false (missed webhooks).

```bash
php artisan otpiq:sync-statuses --limit=100
```

## Testing

```bash
composer test
composer analyse
composer format
```

## Security

See [SECURITY.md](SECURITY.md). Never expose `OTPIQ_API_KEY` or webhook secrets in client-side code, logs, or public repositories.

## License

MIT — see [LICENSE.md](LICENSE.md).

OTPIQ is a commercial messaging service; billing and refunds follow [OTPIQ’s terms](https://otpiq.com/terms-of-use) and [privacy policy](https://otpiq.com/privacy-policy).
