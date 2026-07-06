<?php

namespace App\Services;

use App\Models\NoteType;
use Illuminate\Database\Eloquent\Collection;

class NoteTypeSyncService
{
    public function __construct(private readonly AdminClient $client) {}

    /**
     * @return Collection<int, NoteType>
     */
    public function sync(string $tenantId): Collection
    {
        rescue(function () use ($tenantId) {
            $remote = $this->client->getNoteTypes($tenantId);

            $remoteIds = array_column($remote, 'id');

            foreach ($remote as $nt) {
                $noteType = NoteType::withoutGlobalScope('tenant')->updateOrCreate(
                    ['id' => $nt['id']],
                    ['tenant_id' => $nt['tenant_id'], 'name' => $nt['name']],
                );

                $incomingRoles = [];
                foreach ($nt['permissions'] as $perm) {
                    $incomingRoles[] = $perm['role'];
                    $noteType->permissions()->updateOrCreate(
                        ['role' => $perm['role']],
                        [
                            'can_read' => $perm['can_read'],
                            'can_write' => $perm['can_write'],
                            'can_update' => $perm['can_update'],
                            'can_delete' => $perm['can_delete'],
                        ],
                    );
                }

                $noteType->permissions()->whereNotIn('role', $incomingRoles)->delete();
            }

            NoteType::withoutGlobalScope('tenant')
                ->where('tenant_id', $tenantId)
                ->whereNotIn('id', $remoteIds)
                ->delete();
        });

        return NoteType::withoutGlobalScope('tenant')
            ->where('tenant_id', $tenantId)
            ->with('permissions')
            ->oldest()
            ->get();
    }
}
