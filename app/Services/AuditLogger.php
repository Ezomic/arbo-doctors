<?php

namespace App\Services;

use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Http\Request;

class AuditLogger
{
    public function __construct(private readonly Request $request) {}

    public function log(string $action, User $user, ?string $subjectId = null): void
    {
        $data = [
            'tenant_id' => $user->tenant_id,
            'user_id' => $user->id,
            'user_name' => $user->name,
            'action' => $action,
            'subject_id' => $subjectId,
            'ip_address' => $this->request->ip(),
        ];

        $data['checksum'] = hash_hmac('sha256', json_encode($data), config('app.key'));

        AuditLog::create($data);
    }
}
