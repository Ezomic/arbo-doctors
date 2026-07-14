<?php

use App\Models\AuditLog;
use App\Models\MedicalCase;
use App\Models\NoteType;
use App\Models\RolePermission;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

function doctorWithPermission(string $tenantId, string ...$permissions): User
{
    $user = User::factory()->create(['tenant_id' => $tenantId, 'current_role' => 'doctor']);

    foreach ($permissions as $permission) {
        RolePermission::query()->create([
            'tenant_id' => $tenantId,
            'role_name' => 'doctor',
            'permission' => $permission,
        ]);
    }

    return $user;
}

test('index requires view-medical-cases', function () {
    $tenantId = (string) Str::uuid();
    $user = User::factory()->create(['tenant_id' => $tenantId, 'current_role' => 'doctor']);
    $this->actingAs($user);

    $this->get(route('medical-cases.index'))->assertForbidden();
});

test('a doctor with view-medical-cases can list medical cases', function () {
    $tenantId = (string) Str::uuid();
    $user = doctorWithPermission($tenantId, 'View medical cases');
    $this->actingAs($user);

    Http::fake(['*/api/cases*' => Http::response([])]);

    $this->get(route('medical-cases.index'))->assertOk();
});

test('show requires view-medical-cases', function () {
    $tenantId = (string) Str::uuid();
    $user = User::factory()->create(['tenant_id' => $tenantId, 'current_role' => 'doctor']);
    $this->actingAs($user);

    $medicalCase = MedicalCase::factory()->create(['tenant_id' => $tenantId]);

    $this->get(route('medical-cases.show', $medicalCase))->assertForbidden();
});

test('store requires manage-medical-cases', function () {
    $tenantId = (string) Str::uuid();
    $user = User::factory()->create(['tenant_id' => $tenantId, 'current_role' => 'doctor']);
    $this->actingAs($user);

    $caseId = (string) Str::uuid();

    $response = $this->post(route('medical-cases.store'), ['case_id' => $caseId]);

    $response->assertForbidden();
});

test('a doctor with manage-medical-cases can add a medical case', function () {
    $tenantId = (string) Str::uuid();
    $user = doctorWithPermission($tenantId, 'Manage medical cases');
    $this->actingAs($user);

    $caseId = (string) Str::uuid();

    Http::fake(['*/api/cases/*' => Http::response([
        'employer' => ['name' => 'Acme'],
        'employee' => ['first_name' => 'Jane', 'last_name' => 'Doe'],
    ])]);

    $response = $this->post(route('medical-cases.store'), ['case_id' => $caseId]);

    $response->assertRedirect(route('medical-cases.index'));
    expect(MedicalCase::query()->where('case_id', $caseId)->exists())->toBeTrue();
});

test('update (non-closing) requires manage-medical-cases, not close-medical-cases', function () {
    $tenantId = (string) Str::uuid();
    $user = doctorWithPermission($tenantId, 'Close medical cases');
    $this->actingAs($user);

    $medicalCase = MedicalCase::factory()->create(['tenant_id' => $tenantId, 'status' => 'open']);

    $response = $this->put(route('medical-cases.update', $medicalCase), ['status' => 'open']);

    $response->assertForbidden();
});

test('a doctor with manage-medical-cases can make a non-closing update', function () {
    $tenantId = (string) Str::uuid();
    $user = doctorWithPermission($tenantId, 'Manage medical cases');
    $this->actingAs($user);

    $medicalCase = MedicalCase::factory()->create(['tenant_id' => $tenantId, 'status' => 'open']);

    Http::fake(['*/api/cases/*' => Http::response([])]);

    $response = $this->put(route('medical-cases.update', $medicalCase), [
        'status' => 'open',
        'advice' => 'Rest for a week.',
    ]);

    $response->assertRedirect(route('medical-cases.index'));
    expect($medicalCase->refresh()->advice)->toBe('Rest for a week.');
});

test('closing update requires close-medical-cases, not manage-medical-cases', function () {
    $tenantId = (string) Str::uuid();
    $user = doctorWithPermission($tenantId, 'Manage medical cases');
    $this->actingAs($user);

    $medicalCase = MedicalCase::factory()->create(['tenant_id' => $tenantId, 'status' => 'open']);

    $response = $this->put(route('medical-cases.update', $medicalCase), ['status' => 'closed']);

    $response->assertForbidden();
});

test('a doctor with close-medical-cases can close a medical case', function () {
    $tenantId = (string) Str::uuid();
    $user = doctorWithPermission($tenantId, 'Close medical cases');
    $this->actingAs($user);

    $medicalCase = MedicalCase::factory()->create(['tenant_id' => $tenantId, 'status' => 'open']);

    Http::fake(['*/api/cases/*' => Http::response([])]);

    $response = $this->put(route('medical-cases.update', $medicalCase), ['status' => 'closed']);

    $response->assertRedirect(route('medical-cases.index'));
    expect($medicalCase->refresh()->status)->toBe('closed');
});

test('show renders notes and only the writable note types for the user role', function () {
    $tenantId = (string) Str::uuid();
    $user = doctorWithPermission($tenantId, 'View medical cases');
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
