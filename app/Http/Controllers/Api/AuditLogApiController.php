<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuditLogApiController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $data = $request->validate([
            'tenant_id' => ['required', 'uuid'],
            'per_page' => ['integer', 'min:1', 'max:100'],
        ]);

        $logs = AuditLog::query()
            ->where('tenant_id', $data['tenant_id'])
            ->latest()
            ->paginate($data['per_page'] ?? 50);

        return response()->json([
            'data' => $logs->items(),
            'current_page' => $logs->currentPage(),
            'last_page' => $logs->lastPage(),
            'total' => $logs->total(),
        ]);
    }
}
