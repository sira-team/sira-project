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
        Schema::create('camp_contracts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('camp_id')->constrained('camps')->cascadeOnDelete();
            $table->foreignId('hostel_id')->constrained()->cascadeOnDelete();
            $table->decimal('price_per_person_per_night', 8, 2);
            $table->boolean('catering_included')->default(false);
            $table->integer('contracted_participants');
            $table->integer('contracted_supporters');
            $table->date('contract_date')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('camp_contracts');
    }
};
