<?php

namespace App\Console\Commands;

use App\Models\AuditLog;
use App\Models\MedicalCase;
use App\Models\RetentionRun;
use Carbon\CarbonImmutable;
use Illuminate\Console\Command;

class RetentionCleanupCommand extends Command
{
    protected $signature = 'retention:cleanup {--dry-run : Preview without deleting}';

    protected $description = 'Delete records past their legal retention period (WGBO 20yr medical cases, NEN 7513 5yr audit logs)';

    public function handle(): int
    {
        $dryRun = (bool) $this->option('dry-run');
        $startedAt = CarbonImmutable::now();
        $counts = [];

        // WGBO Art. 454: medical records must be kept for 20 years from closure.
        $expiredCases = MedicalCase::withoutGlobalScope('tenant')
            ->whereNotNull('closed_at')
            ->where('closed_at', '<', now()->subYears(20))
            ->get();

        $counts['medical_cases_deleted'] = $expiredCases->count();
        $this->info("Medical cases past 20-year WGBO retention: {$expiredCases->count()}");

        if (! $dryRun) {
            foreach ($expiredCases as $case) {
                $case->delete();
            }
        }

        // NEN 7513: audit logs minimum 5-year retention; delete after that.
        $expiredAuditLogs = AuditLog::query()
            ->where('created_at', '<', now()->subYears(5))
            ->count();

        $counts['audit_logs_deleted'] = $expiredAuditLogs;
        $this->info("Audit log entries past 5-year NEN 7513 retention: {$expiredAuditLogs}");

        if (! $dryRun) {
            AuditLog::query()->where('created_at', '<', now()->subYears(5))->delete();
        }

        RetentionRun::create([
            'command' => 'retention:cleanup',
            'dry_run' => $dryRun,
            'counts' => $counts,
            'started_at' => $startedAt,
            'finished_at' => CarbonImmutable::now(),
        ]);

        $this->info($dryRun ? 'Dry run — no changes made.' : 'Retention cleanup complete.');

        return self::SUCCESS;
    }
}
