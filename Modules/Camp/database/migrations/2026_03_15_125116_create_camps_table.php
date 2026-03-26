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
        Schema::create('camps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->date('starts_at');
            $table->date('ends_at');
            $table->longText('description')->nullable();
            $table->longText('internal_notes')->nullable();
            $table->string('target_group');
            $table->integer('age_min')->nullable();
            $table->integer('age_max')->nullable();
            $table->enum('gender_policy', ['all', 'male', 'female']);
            $table->boolean('food_provided')->default(false);
            $table->boolean('participants_bring_food')->default(false);
            $table->boolean('registration_open')->default(true);
            $table->dateTime('registration_opens_at')->nullable();
            $table->dateTime('registration_deadline')->nullable();
            $table->decimal('price_per_participant', 8, 2)->default(0);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('camps');
    }
};
