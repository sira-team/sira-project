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
        Schema::create('visitor_children', function (Blueprint $table) {
            $table->foreignId('parent_id')->constrained('visitors')->cascadeOnDelete();
            $table->foreignId('child_id')->constrained('visitors')->cascadeOnDelete();
            $table->string('relationship');
            $table->primary(['parent_id', 'child_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visitor_children');
    }
};
