<?php

use App\Models\ApiClient;
use App\Models\AuditLog;
use Illuminate\Support\Str;

test('an authenticated api client can list audit logs for a tenant', function () {
    $client = ApiClient::query()->create(['name' => 'admin-service']);
    $token = $client->createToken('admin-service', ['audit-logs:read'])->plainTextToken;

    $tenantId = (string) Str::uuid();

    AuditLog::query()->create(['tenant_id' => $tenantId, 'action' => 'medical_case.viewed']);
    AuditLog::query()->create(['tenant_id' => (string) Str::uuid(), 'action' => 'medical_case.viewed']);

    $response = $this->withToken($token)->getJson('/api/audit-logs?tenant_id='.$tenantId);

    $response->assertOk();
    expect($response->json('total'))->toBe(1)
        ->and($response->json('data'))->toHaveCount(1);
});

test('rejects requests without a valid token', function () {
    $this->getJson('/api/audit-logs?tenant_id='.Str::uuid())->assertUnauthorized();
});

test('rejects a token without the audit-logs:read ability', function () {
    $client = ApiClient::query()->create(['name' => 'other-service']);
    $token = $client->createToken('other-service', ['something-else:read'])->plainTextToken;

    $this->withToken($token)->getJson('/api/audit-logs?tenant_id='.Str::uuid())->assertForbidden();
});
