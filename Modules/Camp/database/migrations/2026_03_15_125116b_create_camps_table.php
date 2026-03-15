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
            $table->unsignedBigInteger('hostel_contract_id')->nullable();
            $table->string('name');
            $table->date('starts_at');
            $table->date('ends_at');
            $table->integer('capacity');
            $table->decimal('price', 8, 2);
            $table->enum('target_group', ['juniors', 'adults', 'mixed']);
            $table->integer('age_min')->nullable();
            $table->integer('age_max')->nullable();
            $table->enum('gender_policy', ['mixed', 'separated', 'brothers_only', 'sisters_only']);
            $table->boolean('food_provided')->default(false);
            $table->boolean('participants_bring_food')->default(false);
            $table->integer('predicted_participants')->nullable();
            $table->integer('predicted_supporters')->nullable();
            $table->boolean('registration_open')->default(true);
            $table->dateTime('registration_opens_at')->nullable();
            $table->dateTime('registration_deadline')->nullable();
            $table->string('iban');
            $table->string('bank_recipient');
            $table->text('notes')->nullable();
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
