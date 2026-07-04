<?php

namespace App\Http\Controllers;

use App\Models\MedicalCase;
use App\Models\User;
use App\Services\CaseOfficersClient;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

class MedicalCaseController extends Controller
{
    public function index(CaseOfficersClient $client): Response
    {
        /** @var User $user */
        $user = Auth::user();

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

    public function store(Request $request, CaseOfficersClient $client): RedirectResponse
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

        $this->pushBack($client, $medicalCase);

        Inertia::flash('toast', [
            'type' => 'success',
            'message' => 'Medical case added — outcomes shared with Case Officers.',
        ]);

        return to_route('medical-cases.index');
    }

    public function update(Request $request, MedicalCase $medicalCase, CaseOfficersClient $client): RedirectResponse
    {
        $data = $request->validate([
            'diagnosis_notes' => ['nullable', 'string'],
            'restrictions' => ['nullable', 'string'],
            'advice' => ['nullable', 'string'],
            'expected_return_date' => ['nullable', 'date'],
            'status' => ['required', Rule::in(['open', 'closed'])],
        ]);

        $medicalCase->update([
            ...$data,
            'closed_at' => $data['status'] === 'closed' ? ($medicalCase->closed_at ?? now()) : null,
        ]);

        $this->pushBack($client, $medicalCase);

        Inertia::flash('toast', [
            'type' => 'success',
            'message' => 'Medical case updated — outcomes shared with Case Officers.',
        ]);

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
