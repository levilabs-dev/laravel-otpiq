<?php

declare(strict_types=1);

namespace LeviLabs\LaravelOtpiq\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $project
 * @property string $sms_id
 * @property string|null $phone_number
 * @property string|null $sms_type
 * @property string|null $provider
 * @property string $status
 * @property float|null $cost
 * @property bool $is_final
 * @property string|null $last_channel
 * @property array<int, array<string, mixed>>|null $channel_flow
 * @property string|null $payment_type
 * @property Carbon|null $tracked_at
 */
class OtpiqMessage extends Model
{
    protected $table = 'otpiq_messages';

    protected $fillable = [
        'project',
        'sms_id',
        'phone_number',
        'sms_type',
        'provider',
        'status',
        'cost',
        'is_final',
        'last_channel',
        'channel_flow',
        'payment_type',
        'tracked_at',
    ];

    protected function casts(): array
    {
        return [
            'cost' => 'float',
            'is_final' => 'boolean',
            'channel_flow' => 'array',
            'tracked_at' => 'datetime',
        ];
    }
}
