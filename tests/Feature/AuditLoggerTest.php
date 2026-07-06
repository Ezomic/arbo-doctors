<?php

use App\Models\AuditLog;
use App\Models\User;
use App\Services\AuditLogger;
use Illuminate\Support\Str;

test('log writes a record with a tamper-evident checksum', function () {
    $user = User::factory()->create();
    $subjectId = (string) Str::uuid();

    app(AuditLogger::class)->log('medical_case.viewed', $user, $subjectId);

    $log = AuditLog::query()->where('subject_id', $subjectId)->firstOrFail();

    expect($log->action)->toBe('medical_case.viewed')
        ->and($log->user_id)->toBe($user->id)
        ->and($log->tenant_id)->toBe($user->tenant_id)
        ->and($log->checksum)->not->toBeEmpty();

    $expected = hash_hmac('sha256', json_encode([
        'tenant_id' => $log->tenant_id,
        'user_id' => $log->user_id,
        'user_name' => $log->user_name,
        'action' => $log->action,
        'subject_id' => $log->subject_id,
        'ip_address' => $log->ip_address,
    ]), config('app.key'));

    expect($log->checksum)->toBe($expected);
});
