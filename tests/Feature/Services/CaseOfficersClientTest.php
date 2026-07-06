<?php

use App\Services\CaseOfficersClient;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

test('updateCase pushes the outcome via PATCH, not PUT', function () {
    config(['services.case_officers.token' => 'test-token']);

    $caseId = (string) Str::uuid();
    $tenantId = (string) Str::uuid();

    Http::fake([
        "*/api/cases/{$caseId}" => Http::response(['id' => $caseId]),
    ]);

    app(CaseOfficersClient::class)->updateCase($caseId, $tenantId, [
        'advice' => 'Gradual return to work.',
        'restrictions' => null,
        'expected_return_date' => '2026-08-01',
    ]);

    Http::assertSent(function ($request) use ($caseId) {
        return $request->method() === 'PATCH'
            && str_ends_with($request->url(), "/api/cases/{$caseId}");
    });
});
