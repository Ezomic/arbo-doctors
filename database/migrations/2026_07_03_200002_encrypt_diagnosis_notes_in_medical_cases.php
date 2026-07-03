<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Change diagnosis_notes from text to a longer text type to hold encrypted values.
        // Existing plaintext values will be re-encrypted by the model cast going forward.
        // If there is existing data, a separate one-off script must re-encrypt it.
        Schema::table('medical_cases', function (Blueprint $table) {
            $table->text('diagnosis_notes')->nullable()->change();
        });
    }

    public function down(): void
    {
        // No structural change to reverse — the column type stays text.
    }
};
