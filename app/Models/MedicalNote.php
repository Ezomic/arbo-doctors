<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use RobbinThijssen\IdentitySsoKit\Concerns\HasTenantScope;
use RobbinThijssen\IdentitySsoKit\Concerns\HasUuidPrimaryKey;

#[Fillable(['tenant_id', 'medical_case_id', 'note_type_id', 'user_id', 'body'])]
class MedicalNote extends Model
{
    use HasTenantScope, HasUuidPrimaryKey;

    protected function casts(): array
    {
        return [
            'body' => 'encrypted',
        ];
    }

    /**
     * @return BelongsTo<MedicalCase, $this>
     */
    public function medicalCase(): BelongsTo
    {
        return $this->belongsTo(MedicalCase::class);
    }

    /**
     * @return BelongsTo<NoteType, $this>
     */
    public function noteType(): BelongsTo
    {
        return $this->belongsTo(NoteType::class);
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
