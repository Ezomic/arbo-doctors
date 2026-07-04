<?php

namespace App\Services;

use RobbinThijssen\IdentitySsoKit\Api\InternalApiClient;

/**
 * Case Officers owns the non-medical case shell (employer, employee,
 * status, opened/closed dates) — this app only ever reads it, and only
 * ever writes back the structured, non-medical outcomes a doctor records
 * (advice/restrictions/expected_return_date). The actual medical detail
 * never crosses this boundary.
 */
class CaseOfficersClient extends InternalApiClient
{
    protected function baseUrl(): string
    {
        return config('services.case_officers.base_url');
    }

    protected function token(): string
    {
        return config('services.case_officers.token');
    }

    public function getOpenCases(string $tenantId): array
    {
        return $this->get('cases', ['tenant_id' => $tenantId]);
    }

    public function getCase(string $caseId, string $tenantId): array
    {
        return $this->get("cases/{$caseId}", ['tenant_id' => $tenantId]);
    }

    public function updateCase(string $caseId, string $tenantId, array $data): array
    {
        return $this->patch("cases/{$caseId}", [...$data, 'tenant_id' => $tenantId]);
    }
}
