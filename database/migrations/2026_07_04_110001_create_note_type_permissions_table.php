<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('note_type_permissions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('note_type_id');
            $table->string('role');
            $table->boolean('can_read')->default(false);
            $table->boolean('can_write')->default(false);
            $table->boolean('can_update')->default(false);
            $table->boolean('can_delete')->default(false);
            $table->timestamps();

            $table->unique(['note_type_id', 'role']);
            $table->foreign('note_type_id')->references('id')->on('note_types')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('note_type_permissions');
    }
};
