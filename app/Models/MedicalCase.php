<?php

namespace App\Models;

use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use RobbinThijssen\IdentitySsoKit\Concerns\HasTenantScope;
use RobbinThijssen\IdentitySsoKit\Concerns\HasUuidPrimaryKey;

/**
 * @property string $id
 * @property string $tenant_id
 * @property string $case_id
 * @property string $doctor_user_id
 * @property string $employer_name
 * @property string $employee_first_name
 * @property string $employee_last_name
 * @property string|null $diagnosis_notes
 * @property string|null $restrictions
 * @property string|null $advice
 * @property CarbonImmutable|null $expected_return_date
 * @property string $status
 * @property CarbonImmutable $opened_at
 * @property CarbonImmutable|null $closed_at
 */
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
            'diagnosis_notes' => 'encrypted',
        ];
    }
}
