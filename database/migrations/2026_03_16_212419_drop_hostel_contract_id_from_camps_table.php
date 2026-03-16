<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('camps', function (Blueprint $table) {
            $table->dropForeign(['hostel_contract_id']);
            $table->dropColumn('hostel_contract_id');
        });
    }

    public function down(): void
    {
        Schema::table('camps', function (Blueprint $table) {
            $table->unsignedBigInteger('hostel_contract_id')->nullable();
            $table->foreign('hostel_contract_id')
                ->references('id')
                ->on('hostel_contracts')
                ->cascadeOnDelete();
        });
    }
};
