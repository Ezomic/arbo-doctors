<?php

namespace App\Http\Controllers;

use App\Models\MedicalCase;
use App\Models\MedicalNote;
use App\Models\NoteType;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class MedicalNoteController extends Controller
{
    public function store(Request $request, MedicalCase $medicalCase): RedirectResponse
    {
        $data = $request->validate([
            'note_type_id' => ['required', 'uuid', Rule::exists('note_types', 'id')],
            'body' => ['required', 'string', 'max:10000'],
        ]);

        $noteType = NoteType::with('permissions')->findOrFail((string) $data['note_type_id']);

        $this->authorize('create', [MedicalNote::class, $noteType]);

        $medicalCase->notes()->create([
            'note_type_id' => $noteType->id,
            'user_id' => Auth::id(),
            'body' => $data['body'],
        ]);

        return to_route('medical-cases.show', $medicalCase);
    }

    public function update(Request $request, MedicalCase $medicalCase, MedicalNote $medicalNote): RedirectResponse
    {
        $this->authorize('update', $medicalNote);

        $data = $request->validate([
            'body' => ['required', 'string', 'max:10000'],
        ]);

        $medicalNote->update($data);

        return to_route('medical-cases.show', $medicalCase);
    }

    public function destroy(MedicalCase $medicalCase, MedicalNote $medicalNote): RedirectResponse
    {
        $this->authorize('delete', $medicalNote);

        $medicalNote->delete();

        return to_route('medical-cases.show', $medicalCase);
    }
}
