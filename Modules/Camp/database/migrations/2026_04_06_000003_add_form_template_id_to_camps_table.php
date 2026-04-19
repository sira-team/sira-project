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
            $table->foreignId('form_template_id')->nullable()->constrained('form_templates')->nullOnDelete()->after('tenant_id');
        });
    }

    public function down(): void
    {
        Schema::table('camps', function (Blueprint $table) {
            $table->dropConstrainedForeignId('form_template_id');
        });
    }
};
