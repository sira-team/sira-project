<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('hostel_rooms', function (Blueprint $table) {
            $table->dropColumn('gender');
        });
    }

    public function down(): void
    {
        Schema::table('hostel_rooms', function (Blueprint $table) {
            $table->string('gender')->default('mixed');
        });
    }
};
