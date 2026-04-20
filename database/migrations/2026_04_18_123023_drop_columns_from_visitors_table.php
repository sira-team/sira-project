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
        Schema::table('visitors', function (Blueprint $table) {
            $table->dropColumn('date_of_birth');
            $table->dropColumn('allergies');
            $table->dropColumn('medications');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('visitors', function (Blueprint $table) {
            $table->date('date_of_birth')->nullable();
            $table->text('allergies')->nullable();
            $table->text('medications')->nullable();
        });
    }
};
