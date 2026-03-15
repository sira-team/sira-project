<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('academy_session_tickets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('academy_enrollment_id')->constrained()->cascadeOnDelete();
            $table->foreignId('academy_session_id')->constrained()->cascadeOnDelete();
            $table->string('code')->unique();
            $table->dateTime('issued_at');
            $table->timestamps();

            $table->unique(['academy_enrollment_id', 'academy_session_id'], 'enr_session_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('academy_session_tickets');
    }
};
