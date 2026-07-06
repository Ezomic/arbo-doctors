<?php

use App\Models\AuditLog;
use App\Models\MedicalCase;
use App\Models\RetentionRun;
use Illuminate\Support\Str;

test('deletes medical cases closed more than 20 years ago and audit logs older than 5 years', function () {
    $tenantId = (string) Str::uuid();

    $expiredCase = MedicalCase::factory()->create([
        'tenant_id' => $tenantId,
        'status' => 'closed',
        'closed_at' => now()->subYears(21),
    ]);
    $recentCase = MedicalCase::factory()->create([
        'tenant_id' => $tenantId,
        'status' => 'closed',
        'closed_at' => now()->subYears(2),
    ]);

    $expiredLog = AuditLog::query()->create(['tenant_id' => $tenantId, 'action' => 'medical_case.viewed']);
    $expiredLog->forceFill(['created_at' => now()->subYears(6)])->save();
    $recentLog = AuditLog::query()->create(['tenant_id' => $tenantId, 'action' => 'medical_case.viewed']);
    $recentLog->forceFill(['created_at' => now()->subYear()])->save();

    $this->artisan('retention:cleanup')->assertSuccessful();

    expect(MedicalCase::withoutGlobalScope('tenant')->find($expiredCase->id))->toBeNull()
        ->and(MedicalCase::withoutGlobalScope('tenant')->find($recentCase->id))->not->toBeNull()
        ->and(AuditLog::query()->find($expiredLog->id))->toBeNull()
        ->and(AuditLog::query()->find($recentLog->id))->not->toBeNull();

    $run = RetentionRun::query()->latest()->firstOrFail();
    expect($run->dry_run)->toBeFalse()
        ->and($run->counts['medical_cases_deleted'])->toBe(1)
        ->and($run->counts['audit_logs_deleted'])->toBe(1);
});

test('dry-run mode previews without deleting anything', function () {
    $tenantId = (string) Str::uuid();

    $expiredCase = MedicalCase::factory()->create([
        'tenant_id' => $tenantId,
        'status' => 'closed',
        'closed_at' => now()->subYears(21),
    ]);

    $this->artisan('retention:cleanup', ['--dry-run' => true])->assertSuccessful();

    expect(MedicalCase::withoutGlobalScope('tenant')->find($expiredCase->id))->not->toBeNull();

    $run = RetentionRun::query()->latest()->firstOrFail();
    expect($run->dry_run)->toBeTrue();
});
