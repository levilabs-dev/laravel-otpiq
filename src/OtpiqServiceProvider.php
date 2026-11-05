<?php

declare(strict_types=1);

namespace LeviLabs\LaravelOtpiq;

use Illuminate\Support\Facades\Event;
use LeviLabs\LaravelOtpiq\Console\Commands\SyncOtpiqStatuses;
use LeviLabs\LaravelOtpiq\Contracts\OtpiqMessagingServiceContract;
use LeviLabs\LaravelOtpiq\Contracts\OtpiqPricingServiceContract;
use LeviLabs\LaravelOtpiq\Contracts\OtpiqProjectServiceContract;
use LeviLabs\LaravelOtpiq\Contracts\OtpiqReportServiceContract;
use LeviLabs\LaravelOtpiq\Contracts\OtpiqSenderIdServiceContract;
use LeviLabs\LaravelOtpiq\Contracts\OtpiqWhatsAppCampaignServiceContract;
use LeviLabs\LaravelOtpiq\Contracts\OtpiqWhatsAppResourceServiceContract;
use LeviLabs\LaravelOtpiq\Events\DeliveryStatusUpdated;
use LeviLabs\LaravelOtpiq\Events\MessageSent;
use LeviLabs\LaravelOtpiq\Listeners\PersistOtpiqMessage;
use LeviLabs\LaravelOtpiq\Services\OtpiqMessagingService;
use LeviLabs\LaravelOtpiq\Services\OtpiqPricingService;
use LeviLabs\LaravelOtpiq\Services\OtpiqProjectService;
use LeviLabs\LaravelOtpiq\Services\OtpiqReportService;
use LeviLabs\LaravelOtpiq\Services\OtpiqSenderIdService;
use LeviLabs\LaravelOtpiq\Services\OtpiqWhatsAppCampaignService;
use LeviLabs\LaravelOtpiq\Services\OtpiqWhatsAppResourceService;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class OtpiqServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('otpiq')
            ->hasConfigFile('otpiq')
            ->hasMigrations('create_otpiq_messages_table')
            ->runsMigrations()
            ->hasCommand(SyncOtpiqStatuses::class);
    }

    public function registeringPackage(): void
    {
        $this->app->singleton(OtpiqProjectServiceContract::class, OtpiqProjectService::class);
        $this->app->singleton(OtpiqMessagingServiceContract::class, OtpiqMessagingService::class);
        $this->app->singleton(OtpiqSenderIdServiceContract::class, OtpiqSenderIdService::class);
        $this->app->singleton(OtpiqPricingServiceContract::class, OtpiqPricingService::class);
        $this->app->singleton(OtpiqReportServiceContract::class, OtpiqReportService::class);
        $this->app->singleton(OtpiqWhatsAppResourceServiceContract::class, OtpiqWhatsAppResourceService::class);
        $this->app->singleton(OtpiqWhatsAppCampaignServiceContract::class, OtpiqWhatsAppCampaignService::class);
    }

    public function packageBooted(): void
    {
        Event::listen(MessageSent::class, [PersistOtpiqMessage::class, 'onSent']);
        Event::listen(DeliveryStatusUpdated::class, [PersistOtpiqMessage::class, 'onDeliveryUpdated']);
    }
}
