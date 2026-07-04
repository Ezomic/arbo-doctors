<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // Append-only log of each retention cleanup run — satisfies the AVG/NEN 7513
    // requirement to document what was processed and when. Never update rows here.
    public function up(): void
    {
        Schema::create('retention_runs', function (Blueprint $table) {
            $table->id();
            $table->string('command');
            $table->boolean('dry_run');
            $table->json('counts'); // {"medical_cases_deleted": 0, "audit_logs_deleted": 0}
            $table->timestamp('started_at');
            $table->timestamp('finished_at')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('retention_runs');
    }
};
