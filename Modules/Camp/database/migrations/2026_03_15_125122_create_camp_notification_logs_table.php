<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('camp_notification_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('camp_registration_id')->constrained()->cascadeOnDelete();
            $table->enum('type', ['registration_received', 'confirmed', 'waitlisted', 'waitlist_promoted', 'payment_reminder', 'room_assigned', 'cancelled']);
            $table->dateTime('sent_at');
            $table->string('recipient_email');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('camp_notification_logs');
    }
};
