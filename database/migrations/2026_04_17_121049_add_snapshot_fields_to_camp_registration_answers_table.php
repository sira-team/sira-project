<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('camp_registration_answers', function (Blueprint $table) {
            // Snapshot the label and type at submission time so answers survive
            // template edits or field deletions without losing their context.
            $table->string('field_label')->nullable()->after('form_template_field_id');
            $table->string('field_type')->nullable()->after('field_label');
        });
    }

    public function down(): void
    {
        Schema::table('camp_registration_answers', function (Blueprint $table) {
            $table->dropColumn(['field_label', 'field_type']);
        });
    }
};
