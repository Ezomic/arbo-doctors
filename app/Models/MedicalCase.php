<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use RobbinThijssen\IdentitySsoKit\Concerns\HasTenantScope;
use RobbinThijssen\IdentitySsoKit\Concerns\HasUuidPrimaryKey;

#[Fillable([
    'tenant_id', 'case_id', 'doctor_user_id', 'employer_name', 'employee_first_name', 'employee_last_name',
    'diagnosis_notes', 'restrictions', 'advice', 'expected_return_date', 'status', 'opened_at', 'closed_at',
])]
class MedicalCase extends Model
{
    use HasTenantScope, HasUuidPrimaryKey;

    protected function casts(): array
    {
        return [
            'opened_at' => 'datetime',
            'closed_at' => 'datetime',
            'expected_return_date' => 'date',
        ];
    }
}
