<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('medical_notes', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('tenant_id');
            $table->uuid('medical_case_id');
            $table->uuid('note_type_id');
            $table->uuid('user_id');
            $table->text('body');
            $table->timestamps();

            $table->index(['tenant_id', 'medical_case_id']);
            $table->foreign('medical_case_id')->references('id')->on('medical_cases')->cascadeOnDelete();
            $table->foreign('note_type_id')->references('id')->on('note_types')->restrictOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('medical_notes');
    }
};
