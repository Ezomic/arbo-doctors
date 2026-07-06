<?php

namespace App\Http\Controllers;

use App\Models\MedicalCase;
use App\Models\MedicalNote;
use App\Models\NoteType;
use App\Models\User;
use App\Services\AuditLogger;
use App\Services\CaseOfficersClient;
use App\Services\NoteTypeSyncService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

class MedicalCaseController extends Controller
{
    public function index(CaseOfficersClient $client, AuditLogger $audit): Response
    {
        /** @var User $user */
        $user = Auth::user();

        $audit->log('medical_case.list_viewed', $user);

        $medicalCases = MedicalCase::query()->latest('opened_at')->get();
        $claimedCaseIds = $medicalCases->pluck('case_id')->all();

        $openCases = collect($client->getOpenCases($user->tenant_id))
            ->reject(fn (array $case) => in_array($case['id'], $claimedCaseIds, true))
            ->values();

        return Inertia::render('medical-cases/Index', [
            'medicalCases' => $medicalCases,
            'openCases' => $openCases,
        ]);
    }

    public function store(Request $request, CaseOfficersClient $client, AuditLogger $audit): RedirectResponse
    {
        /** @var User $user */
        $user = Auth::user();

        $data = $request->validate([
            'case_id' => [
                'required',
                'uuid',
                Rule::unique('medical_cases', 'case_id')->where('tenant_id', $user->tenant_id),
            ],
            'diagnosis_notes' => ['nullable', 'string'],
            'restrictions' => ['nullable', 'string'],
            'advice' => ['nullable', 'string'],
            'expected_return_date' => ['nullable', 'date'],
        ]);

        $caseData = $client->getCase($data['case_id'], $user->tenant_id);

        $medicalCase = MedicalCase::query()->create([
            'case_id' => $data['case_id'],
            'doctor_user_id' => $user->id,
            'employer_name' => $caseData['employer']['name'],
            'employee_first_name' => $caseData['employee']['first_name'],
            'employee_last_name' => $caseData['employee']['last_name'],
            'diagnosis_notes' => $data['diagnosis_notes'] ?? null,
            'restrictions' => $data['restrictions'] ?? null,
            'advice' => $data['advice'] ?? null,
            'expected_return_date' => $data['expected_return_date'] ?? null,
        ]);

        $audit->log('medical_case.created', $user, $medicalCase->id);
        $this->pushBack($client, $medicalCase);

        return to_route('medical-cases.index');
    }

    public function show(MedicalCase $medicalCase, NoteTypeSyncService $noteTypeSync, AuditLogger $audit): Response
    {
        /** @var User $user */
        $user = Auth::user();

        $audit->log('medical_case.viewed', $user, $medicalCase->id);

        $noteTypes = $noteTypeSync->sync($medicalCase->tenant_id);
        $userRole = $user->current_role ?? '';

        $readableTypeIds = $noteTypes
            ->filter(fn (NoteType $nt) => $nt->permissionFor($userRole)?->can_read === true)
            ->pluck('id');

        $notes = $medicalCase->notes()
            ->with(['noteType:id,name', 'author:id,name'])
            ->where(fn ($q) => $q
                ->whereIn('note_type_id', $readableTypeIds)
                ->orWhere('user_id', $user->id)
            )
            ->oldest()
            ->get()
            ->map(function (MedicalNote $note) use ($user, $userRole) {
                $permission = $note->noteType->permissionFor($userRole);
                $isMine = $note->user_id === $user->id;

                return [
                    'id' => $note->id,
                    'note_type_id' => $note->note_type_id,
                    'note_type_name' => $note->noteType->name,
                    'body' => $note->body,
                    'author_name' => $note->author->name,
                    'is_mine' => $isMine,
                    'can_update' => $isMine || $permission?->can_update === true,
                    'can_delete' => $isMine || $permission?->can_delete === true,
                    'created_at' => $note->created_at,
                ];
            });

        return Inertia::render('medical-cases/Show', [
            'medicalCase' => $medicalCase,
            'notes' => $notes,
            'writableNoteTypes' => $noteTypes
                ->filter(fn (NoteType $noteType) => $noteType->permissionFor($userRole)?->can_write === true)
                ->map(fn (NoteType $noteType) => ['id' => $noteType->id, 'name' => $noteType->name])
                ->values(),
        ]);
    }

    public function update(Request $request, MedicalCase $medicalCase, CaseOfficersClient $client, AuditLogger $audit): RedirectResponse
    {
        $data = $request->validate([
            'diagnosis_notes' => ['nullable', 'string'],
            'restrictions' => ['nullable', 'string'],
            'advice' => ['nullable', 'string'],
            'expected_return_date' => ['nullable', 'date'],
            'status' => ['required', Rule::in(['open', 'closed'])],
        ]);

        $closing = $data['status'] === 'closed' && $medicalCase->closed_at === null;

        $medicalCase->update([
            ...$data,
            'closed_at' => $data['status'] === 'closed' ? ($medicalCase->closed_at ?? now()) : null,
        ]);

        /** @var User $user */
        $user = Auth::user();
        $audit->log($closing ? 'medical_case.closed' : 'medical_case.updated', $user, $medicalCase->id);
        $this->pushBack($client, $medicalCase);

        return to_route('medical-cases.index');
    }

    /**
     * Only structured, non-medical outcomes are pushed back — Case
     * Officers never receives diagnosis_notes.
     */
    private function pushBack(CaseOfficersClient $client, MedicalCase $medicalCase): void
    {
        $client->updateCase($medicalCase->case_id, $medicalCase->tenant_id, [
            'advice' => $medicalCase->advice,
            'restrictions' => $medicalCase->restrictions,
            'expected_return_date' => $medicalCase->expected_return_date?->toDateString(),
        ]);
    }
}
