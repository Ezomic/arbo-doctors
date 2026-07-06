<?php

namespace App\Policies;

use App\Models\MedicalNote;
use App\Models\NoteType;
use App\Models\User;

class MedicalNotePolicy
{
    public function create(User $user, NoteType $noteType): bool
    {
        $perm = $noteType->permissionFor($user->current_role ?? '');

        return $perm?->can_write === true;
    }

    public function update(User $user, MedicalNote $note): bool
    {
        if ($note->user_id === $user->id) {
            return true;
        }

        $perm = $note->noteType->permissionFor($user->current_role ?? '');

        return $perm?->can_update === true;
    }

    public function delete(User $user, MedicalNote $note): bool
    {
        if ($note->user_id === $user->id) {
            return true;
        }

        $perm = $note->noteType->permissionFor($user->current_role ?? '');

        return $perm?->can_delete === true;
    }
}
