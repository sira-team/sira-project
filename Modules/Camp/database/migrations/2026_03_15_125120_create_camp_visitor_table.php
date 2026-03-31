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
        Schema::create('camp_visitor', function (Blueprint $table) {
            $table->id();
            $table->foreignId('camp_id')->constrained()->cascadeOnDelete();
            $table->foreignId('visitor_id')->constrained()->cascadeOnDelete();
            $table->string('status');
            $table->text('wishes')->nullable();
            $table->foreignId('room_id')->nullable()->constrained('hostel_rooms')->nullOnDelete();
            $table->unsignedInteger('waitlist_position')->nullable();
            $table->timestamp('registered_at');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('camp_visitor');
    }
};
