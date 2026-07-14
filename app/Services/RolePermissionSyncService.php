<?php

namespace App\Services;

use App\Models\RolePermission;
use Illuminate\Support\Collection;

class RolePermissionSyncService
{
    public function __construct(private readonly AdminClient $client) {}

    /** @return Collection<int, string> */
    public function permissionsFor(string $tenantId, string $roleName): Collection
    {
        $cached = RolePermission::query()
            ->where('tenant_id', $tenantId)
            ->where('role_name', $roleName)
            ->pluck('permission');

        if ($cached->isNotEmpty()) {
            return $cached;
        }

        $remote = rescue(fn () => $this->client->getRolePermissions($tenantId, $roleName), []);

        RolePermission::query()
            ->where('tenant_id', $tenantId)
            ->where('role_name', $roleName)
            ->delete();

        foreach ($remote as $permission) {
            RolePermission::query()->create([
                'tenant_id' => $tenantId,
                'role_name' => $roleName,
                'permission' => $permission,
            ]);
        }

        return collect($remote);
    }

    public function invalidate(string $tenantId, string $roleName): void
    {
        RolePermission::query()
            ->where('tenant_id', $tenantId)
            ->where('role_name', $roleName)
            ->delete();
    }
}
