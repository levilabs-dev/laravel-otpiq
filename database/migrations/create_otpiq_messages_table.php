<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('otpiq_messages', function (Blueprint $table) {
            $table->id();
            $table->string('project')->default('default')->index();
            $table->string('sms_id')->unique();
            $table->string('phone_number');
            $table->string('sms_type')->nullable();
            $table->string('provider')->nullable();
            $table->string('status')->default('pending')->index();
            $table->decimal('cost', 12, 2)->nullable();
            $table->boolean('is_final')->default(false)->index();
            $table->string('last_channel')->nullable();
            $table->json('channel_flow')->nullable();
            $table->string('payment_type')->nullable();
            $table->timestamp('tracked_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('otpiq_messages');
    }
};
