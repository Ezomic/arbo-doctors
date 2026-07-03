<?php

namespace App\Console\Commands;

use App\Models\MedicalCase;
use Illuminate\Console\Command;

class RetentionCleanupCommand extends Command
{
    protected $signature = 'retention:cleanup {--dry-run : Preview without deleting}';

    protected $description = 'Delete medical cases past the WGBO 20-year retention period';

    public function handle(): int
    {
        $dryRun = $this->option('dry-run');

        // WGBO Art. 454: medical records must be kept for 20 years from closure.
        $expired = MedicalCase::withoutGlobalScope('tenant')
            ->whereNotNull('closed_at')
            ->where('closed_at', '<', now()->subYears(20))
            ->get();

        $this->info("Medical cases past 20-year WGBO retention: {$expired->count()}");

        if (! $dryRun) {
            foreach ($expired as $case) {
                $case->delete();
            }
            $this->info('Retention cleanup complete.');
        } else {
            $this->warn('Dry run — no changes made.');
        }

        return self::SUCCESS;
    }
}
