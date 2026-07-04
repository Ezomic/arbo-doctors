<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    public const UPDATED_AT = null; // append-only

    protected $fillable = [
        'tenant_id', 'user_id', 'user_name', 'action', 'subject_id', 'ip_address', 'checksum',
    ];
}
