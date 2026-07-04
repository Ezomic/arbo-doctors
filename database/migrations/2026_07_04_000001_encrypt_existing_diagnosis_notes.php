<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('medical_cases')
            ->whereNotNull('diagnosis_notes')
            ->lazyById()
            ->each(function (object $row): void {
                // Skip rows that are already encrypted (valid base64 JSON payload).
                if ($this->isAlreadyEncrypted($row->diagnosis_notes)) {
                    return;
                }

                DB::table('medical_cases')
                    ->where('id', $row->id)
                    ->update(['diagnosis_notes' => Crypt::encryptString($row->diagnosis_notes)]);
            });
    }

    public function down(): void
    {
        DB::table('medical_cases')
            ->whereNotNull('diagnosis_notes')
            ->lazyById()
            ->each(function (object $row): void {
                try {
                    DB::table('medical_cases')
                        ->where('id', $row->id)
                        ->update(['diagnosis_notes' => Crypt::decryptString($row->diagnosis_notes)]);
                } catch (\Throwable) {
                    // Already plaintext — leave it.
                }
            });
    }

    private function isAlreadyEncrypted(string $value): bool
    {
        try {
            $decoded = json_decode(base64_decode($value), true);

            return isset($decoded['iv'], $decoded['value'], $decoded['mac']);
        } catch (\Throwable) {
            return false;
        }
    }
};
