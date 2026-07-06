<?php

use App\Models\MedicalCase;
use App\Models\MedicalNote;
use App\Models\NoteType;
use App\Models\User;
use Illuminate\Support\Str;

function actingDoctor(string $tenantId, string $role = 'doctor'): User
{
    return User::factory()->create(['tenant_id' => $tenantId, 'current_role' => $role]);
}

test('a user with write permission can add a note', function () {
    $tenantId = (string) Str::uuid();
    $user = actingDoctor($tenantId);
    $this->actingAs($user);

    $medicalCase = MedicalCase::factory()->create(['tenant_id' => $tenantId]);
    $noteType = NoteType::factory()->create(['tenant_id' => $tenantId]);
    $noteType->permissions()->create(['role' => 'doctor', 'can_write' => true]);

    $response = $this->post(route('medical-notes.store', $medicalCase), [
        'note_type_id' => $noteType->id,
        'body' => 'Patient reports improvement.',
    ]);

    $response->assertRedirect(route('medical-cases.show', $medicalCase));
    expect($medicalCase->notes()->count())->toBe(1);
    expect($medicalCase->notes()->first()->body)->toBe('Patient reports improvement.');
});

test('a user without write permission cannot add a note', function () {
    $tenantId = (string) Str::uuid();
    $user = actingDoctor($tenantId);
    $this->actingAs($user);

    $medicalCase = MedicalCase::factory()->create(['tenant_id' => $tenantId]);
    $noteType = NoteType::factory()->create(['tenant_id' => $tenantId]);
    $noteType->permissions()->create(['role' => 'doctor', 'can_write' => false]);

    $response = $this->post(route('medical-notes.store', $medicalCase), [
        'note_type_id' => $noteType->id,
        'body' => 'Should not be allowed.',
    ]);

    $response->assertForbidden();
    expect($medicalCase->notes()->count())->toBe(0);
});

test('the author can update their own note regardless of role permission', function () {
    $tenantId = (string) Str::uuid();
    $user = actingDoctor($tenantId);
    $this->actingAs($user);

    $medicalCase = MedicalCase::factory()->create(['tenant_id' => $tenantId]);
    $noteType = NoteType::factory()->create(['tenant_id' => $tenantId]);
    $noteType->permissions()->create(['role' => 'doctor', 'can_write' => true, 'can_update' => false]);

    $note = $medicalCase->notes()->create([
        'note_type_id' => $noteType->id,
        'user_id' => $user->id,
        'body' => 'Original.',
    ]);

    $response = $this->put(route('medical-notes.update', [$medicalCase, $note]), [
        'body' => 'Updated by author.',
    ]);

    $response->assertRedirect(route('medical-cases.show', $medicalCase));
    expect($note->fresh()->body)->toBe('Updated by author.');
});

test('a non-author without update permission cannot update a note', function () {
    $tenantId = (string) Str::uuid();
    $author = actingDoctor($tenantId);
    $otherUser = actingDoctor($tenantId);
    $this->actingAs($otherUser);

    $medicalCase = MedicalCase::factory()->create(['tenant_id' => $tenantId]);
    $noteType = NoteType::factory()->create(['tenant_id' => $tenantId]);
    $noteType->permissions()->create(['role' => 'doctor', 'can_write' => true, 'can_update' => false]);

    $note = $medicalCase->notes()->create([
        'note_type_id' => $noteType->id,
        'user_id' => $author->id,
        'body' => 'Original.',
    ]);

    $response = $this->put(route('medical-notes.update', [$medicalCase, $note]), [
        'body' => 'Attempted update.',
    ]);

    $response->assertForbidden();
    expect($note->fresh()->body)->toBe('Original.');
});

test('the author can delete their own note', function () {
    $tenantId = (string) Str::uuid();
    $user = actingDoctor($tenantId);
    $this->actingAs($user);

    $medicalCase = MedicalCase::factory()->create(['tenant_id' => $tenantId]);
    $noteType = NoteType::factory()->create(['tenant_id' => $tenantId]);

    $note = $medicalCase->notes()->create([
        'note_type_id' => $noteType->id,
        'user_id' => $user->id,
        'body' => 'To be deleted.',
    ]);

    $response = $this->delete(route('medical-notes.destroy', [$medicalCase, $note]));

    $response->assertRedirect(route('medical-cases.show', $medicalCase));
    expect(MedicalNote::query()->find($note->id))->toBeNull();
});
