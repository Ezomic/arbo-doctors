<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use RobbinThijssen\IdentitySsoKit\Concerns\HasUuidPrimaryKey;

#[Fillable(['note_type_id', 'role', 'can_read', 'can_write', 'can_update', 'can_delete'])]
class NoteTypePermission extends Model
{
    use HasUuidPrimaryKey;

    protected function casts(): array
    {
        return [
            'can_read'   => 'boolean',
            'can_write'  => 'boolean',
            'can_update' => 'boolean',
            'can_delete' => 'boolean',
        ];
    }

    /**
     * @return BelongsTo<NoteType, $this>
     */
    public function noteType(): BelongsTo
    {
        return $this->belongsTo(NoteType::class);
    }
}
