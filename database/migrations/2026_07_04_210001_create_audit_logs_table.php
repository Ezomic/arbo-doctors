<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // NEN 7513 — tamper-evident, append-only log of every access to medical records.
    // 5-year minimum retention; deleted only by the automated retention job (THI-90).
    public function up(): void
    {
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->uuid('tenant_id')->index();
            $table->uuid('user_id')->nullable()->index();
            $table->string('user_name')->nullable();
            $table->string('action'); // medical_case.viewed, medical_case.created, medical_case.updated, medical_case.closed
            $table->uuid('subject_id')->nullable()->index(); // medical_case UUID
            $table->string('ip_address', 45)->nullable();
            $table->string('checksum', 64)->nullable(); // HMAC-SHA256 for tamper-evidence
            $table->timestamp('created_at')->useCurrent();
            // No updated_at — append-only
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};
