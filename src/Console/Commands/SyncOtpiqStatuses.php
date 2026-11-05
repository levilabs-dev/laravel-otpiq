<?php

declare(strict_types=1);

namespace LeviLabs\LaravelOtpiq\Console\Commands;

use Illuminate\Console\Command;
use LeviLabs\LaravelOtpiq\Contracts\OtpiqMessagingServiceContract;
use LeviLabs\LaravelOtpiq\Models\OtpiqMessage;
use Throwable;

class SyncOtpiqStatuses extends Command
{
    protected $signature = 'otpiq:sync-statuses {--limit=100}';

    protected $description = 'Re-check non-final OTPIQ messages directly from the track API';

    public function handle(OtpiqMessagingServiceContract $messaging): int
    {
        $limitOption = $this->option('limit');
        $limit = is_numeric($limitOption) ? (int) $limitOption : 100;

        $messages = OtpiqMessage::query()
            ->where('is_final', false)
            ->limit($limit)
            ->get();

        foreach ($messages as $message) {
            try {
                $messaging->track($message->sms_id, $message->project);
            } catch (Throwable $e) {
                $this->warn("Message {$message->sms_id} could not be re-checked: {$e->getMessage()}");
            }
        }

        $this->info("Re-checked {$messages->count()} message(s).");

        return self::SUCCESS;
    }
}
