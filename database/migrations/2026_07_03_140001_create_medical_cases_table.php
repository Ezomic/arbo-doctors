<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Fully isolated from Case Officers' own database — Dutch medical-
     * confidentiality law (bedrijfsarts beroepsgeheim) is why diagnosis
     * detail lives only here, never in the shared `cases` record. `case_id`
     * is an external reference to Case Officers' own `cases.id` (no FK,
     * different database/service) — one medical file per case, enforced
     * by the unique index below. Employer/employee names are captured at
     * creation time rather than kept in sync, since a medical file is a
     * point-in-time record.
     */
    public function up(): void
    {
        Schema::create('medical_cases', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('tenant_id');
            $table->uuid('case_id');
            $table->uuid('doctor_user_id');
            $table->string('employer_name');
            $table->string('employee_first_name');
            $table->string('employee_last_name');
            $table->text('diagnosis_notes')->nullable();
            $table->text('restrictions')->nullable();
            $table->text('advice')->nullable();
            $table->date('expected_return_date')->nullable();
            $table->string('status')->default('open');
            $table->timestamp('opened_at')->useCurrent();
            $table->timestamp('closed_at')->nullable();
            $table->timestamps();

            $table->index('tenant_id');
            $table->unique(['tenant_id', 'case_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medical_cases');
    }
};
