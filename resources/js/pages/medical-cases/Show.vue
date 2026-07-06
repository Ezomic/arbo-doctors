<script setup lang="ts">
import { Form, Head } from '@inertiajs/vue3';
import { Pencil, Trash2 } from '@lucide/vue';
import { ref } from 'vue';
import Heading from '@/components/Heading.vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Label } from '@/components/ui/label';
import { Textarea } from '@/components/ui/textarea';
import { formatDateTime } from '@/lib/date';
import { index } from '@/routes/medical-cases';

type MedicalCase = {
    id: string;
    employer_name: string;
    employee_first_name: string;
    employee_last_name: string;
    status: string;
    opened_at: string;
    closed_at: string | null;
};

type MedicalNote = {
    id: string;
    note_type_id: string;
    note_type_name: string;
    body: string;
    author_name: string;
    is_mine: boolean;
    can_update: boolean;
    can_delete: boolean;
    created_at: string;
};

type NoteType = { id: string; name: string };

defineProps<{
    medicalCase: MedicalCase;
    notes: MedicalNote[];
    writableNoteTypes: NoteType[];
}>();

defineOptions({
    layout: {
        breadcrumbs: [{ title: 'Medical Cases', href: index() }],
    },
});

const editingNote = ref<MedicalNote | null>(null);
</script>

<template>
    <Head :title="`${medicalCase.employee_first_name} ${medicalCase.employee_last_name}`" />

    <div class="flex flex-col gap-6 p-4">
        <Heading
            :title="`${medicalCase.employee_first_name} ${medicalCase.employee_last_name}`"
            :description="medicalCase.employer_name"
        />

        <div class="grid gap-4 md:grid-cols-2">
            <div class="rounded-lg border p-4">
                <h2 class="mb-4 font-medium">Case</h2>
                <dl class="space-y-3 text-sm">
                    <div>
                        <dt class="text-muted-foreground">Status</dt>
                        <dd class="font-medium">{{ medicalCase.status }}</dd>
                    </div>
                    <div>
                        <dt class="text-muted-foreground">Opened</dt>
                        <dd class="font-medium">{{ formatDateTime(medicalCase.opened_at) }}</dd>
                    </div>
                    <div v-if="medicalCase.closed_at">
                        <dt class="text-muted-foreground">Closed</dt>
                        <dd class="font-medium">{{ formatDateTime(medicalCase.closed_at) }}</dd>
                    </div>
                </dl>
            </div>

            <div class="rounded-lg border p-4">
                <h2 class="mb-4 font-medium">Notes</h2>

                <ul v-if="notes.length > 0" class="mb-4 space-y-3">
                    <li v-for="note in notes" :key="note.id" class="rounded-md border p-3 text-sm">
                        <div class="mb-1 flex items-center justify-between gap-2">
                            <span class="font-medium">{{ note.note_type_name }}</span>
                            <div class="flex shrink-0 items-center gap-1 text-xs text-muted-foreground">
                                <span>{{ note.author_name }} · {{ formatDateTime(note.created_at) }}</span>
                                <Button v-if="note.can_update" variant="ghost" size="icon" class="size-6" @click="editingNote = note">
                                    <Pencil class="size-3" />
                                </Button>
                                <Form v-if="note.can_delete" :action="`/medical-cases/${medicalCase.id}/notes/${note.id}`" method="delete" class="inline">
                                    <Button type="submit" variant="ghost" size="icon" class="size-6">
                                        <Trash2 class="size-3" />
                                    </Button>
                                </Form>
                            </div>
                        </div>
                        <p class="whitespace-pre-wrap text-muted-foreground">{{ note.body }}</p>
                    </li>
                </ul>
                <p v-else class="mb-4 text-sm text-muted-foreground">No notes yet.</p>

                <Form
                    v-if="writableNoteTypes.length > 0"
                    :action="`/medical-cases/${medicalCase.id}/notes`"
                    method="post"
                    v-slot="{ errors, processing }"
                    :reset-on-success="['body']"
                    class="space-y-3"
                >
                    <div class="grid gap-2">
                        <Label for="note_type_id">Note type</Label>
                        <select
                            id="note_type_id"
                            name="note_type_id"
                            required
                            class="h-9 rounded-md border border-input bg-transparent px-3 text-sm shadow-xs"
                        >
                            <option v-for="nt in writableNoteTypes" :key="nt.id" :value="nt.id">{{ nt.name }}</option>
                        </select>
                        <InputError :message="errors.note_type_id" />
                    </div>
                    <div class="grid gap-2">
                        <Label for="note_body">Note</Label>
                        <Textarea id="note_body" name="body" required placeholder="Write a note…" />
                        <InputError :message="errors.body" />
                    </div>
                    <Button type="submit" :disabled="processing">Add note</Button>
                </Form>
            </div>
        </div>
    </div>

    <!-- Edit note dialog -->
    <template v-if="editingNote">
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/40" @click.self="editingNote = null">
            <div class="w-full max-w-md rounded-lg border bg-background p-6 shadow-lg">
                <h3 class="mb-4 font-medium">Edit note — {{ editingNote.note_type_name }}</h3>
                <Form
                    :action="`/medical-cases/${medicalCase.id}/notes/${editingNote.id}`"
                    method="put"
                    v-slot="{ errors, processing }"
                    class="space-y-3"
                >
                    <div class="grid gap-2">
                        <Label for="edit_note_body">Note</Label>
                        <Textarea id="edit_note_body" name="body" required :default-value="editingNote.body" />
                        <InputError :message="errors.body" />
                    </div>
                    <div class="flex gap-2">
                        <Button type="submit" :disabled="processing">Save</Button>
                        <Button type="button" variant="outline" @click="editingNote = null">Cancel</Button>
                    </div>
                </Form>
            </div>
        </div>
    </template>
</template>
