<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['tenant_id', 'user_id', 'user_name', 'action', 'subject_id', 'ip_address', 'checksum'])]
class AuditLog extends Model
{
    public const UPDATED_AT = null; // append-only
}
