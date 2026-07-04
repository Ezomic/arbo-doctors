<?php

namespace App\Services;

use RobbinThijssen\IdentitySsoKit\Api\InternalApiClient;

class AdminClient extends InternalApiClient
{
    protected function baseUrl(): string
    {
        return config('services.admin.base_url');
    }

    protected function token(): string
    {
        return config('services.admin.token');
    }

    public function getNoteTypes(string $tenantId): array
    {
        return $this->get('note-types', ['tenant_id' => $tenantId, 'app_slug' => 'doctors']);
    }
}
