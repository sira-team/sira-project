<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('camp_registration_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('camp_visitor_id')->constrained('camp_visitor')->cascadeOnDelete();
            $table->foreignId('form_template_field_id')->nullable()->constrained()->nullOnDelete();
            $table->text('value')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('camp_registration_answers');
    }
};
