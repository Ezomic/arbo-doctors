<?php

use App\Models\AuditLog;
use App\Models\MedicalCase;
use App\Models\NoteType;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

test('show renders notes and only the writable note types for the user role', function () {
    $tenantId = (string) Str::uuid();
    $user = User::factory()->create(['tenant_id' => $tenantId, 'current_role' => 'doctor']);
    $this->actingAs($user);

    Http::fake(['*/api/note-types*' => Http::response([])]);

    $medicalCase = MedicalCase::factory()->create(['tenant_id' => $tenantId]);

    $writableType = NoteType::factory()->create(['tenant_id' => $tenantId, 'name' => 'Progress note']);
    $writableType->permissions()->create(['role' => 'doctor', 'can_write' => true]);

    $readOnlyType = NoteType::factory()->create(['tenant_id' => $tenantId, 'name' => 'Confidential']);
    $readOnlyType->permissions()->create(['role' => 'doctor', 'can_write' => false, 'can_read' => true]);

    $note = $medicalCase->notes()->create([
        'note_type_id' => $writableType->id,
        'user_id' => $user->id,
        'body' => 'Note body.',
    ]);

    $response = $this->get(route('medical-cases.show', $medicalCase));

    $response->assertOk();
    $response->assertInertia(fn ($page) => $page
        ->component('medical-cases/Show')
        ->has('notes', 1)
        ->where('notes.0.id', $note->id)
        ->where('notes.0.is_mine', true)
        ->has('writableNoteTypes', 1)
        ->where('writableNoteTypes.0.id', $writableType->id)
    );

    expect(AuditLog::query()->where('subject_id', $medicalCase->id)->where('action', 'medical_case.viewed')->exists())->toBeTrue();
});
