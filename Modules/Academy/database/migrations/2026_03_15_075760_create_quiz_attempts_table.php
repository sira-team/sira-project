<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('quiz_attempts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('academy_enrollment_id')->constrained()->cascadeOnDelete();
            $table->foreignId('quiz_id')->constrained()->cascadeOnDelete();
            $table->foreignId('academy_session_ticket_id')->constrained()->cascadeOnDelete();
            $table->dateTime('attempted_at');
            $table->dateTime('completed_at')->nullable();
            $table->unsignedTinyInteger('score_percent')->nullable();
            $table->boolean('passed')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quiz_attempts');
    }
};
