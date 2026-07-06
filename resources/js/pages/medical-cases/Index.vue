<script setup lang="ts">
import { Form, Head } from '@inertiajs/vue3';
import { Pencil, Plus, Search, Stethoscope } from '@lucide/vue';
import { computed, ref } from 'vue';
import CaseStatusBadge from '@/components/CaseStatusBadge.vue';
import Heading from '@/components/Heading.vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import {
    Dialog,
    DialogContent,
    DialogHeader,
    DialogTitle,
} from '@/components/ui/dialog';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Textarea } from '@/components/ui/textarea';
import { formatDate } from '@/lib/date';
import { store, update } from '@/routes/medical-cases';

type OpenCase = {
    id: string;
    case_type: string | null;
    employer: { id: string; name: string };
    employee: { id: string; first_name: string; last_name: string };
};

type MedicalCase = {
    id: string;
    case_id: string;
    employer_name: string;
    employee_first_name: string;
    employee_last_name: string;
    diagnosis_notes: string | null;
    restrictions: string | null;
    advice: string | null;
    expected_return_date: string | null;
    status: string;
    opened_at: string;
};

const props = defineProps<{
    medicalCases: MedicalCase[];
    openCases: OpenCase[];
}>();

const showCreateDialog = ref(false);
const editingCase = ref<MedicalCase | null>(null);
const query = ref('');

const filteredCases = computed(() => {
    const q = query.value.trim().toLowerCase();

    if (!q) {
        return props.medicalCases;
    }

    return props.medicalCases.filter((medicalCase) =>
        `${medicalCase.employee_first_name} ${medicalCase.employee_last_name} ${medicalCase.employer_name}`
            .toLowerCase()
            .includes(q),
    );
});
</script>

<template>
    <Head title="Medical Cases" />

    <div class="flex flex-col gap-6 p-4">
        <div class="flex items-start justify-between gap-4">
            <Heading
                title="Medical Cases"
                description="Diagnosis and clinical detail stay here — only advice, restrictions, and expected return date are shared with Case Officers"
            />
            <Button
                v-if="openCases.length > 0"
                class="shrink-0"
                @click="showCreateDialog = true"
            >
                <Plus class="size-4" />
                Add medical case
            </Button>
        </div>

        <div v-if="medicalCases.length > 0" class="relative w-full sm:max-w-xs">
            <Search
                class="pointer-events-none absolute top-1/2 left-3 size-4 -translate-y-1/2 text-muted-foreground"
            />
            <Input
                v-model="query"
                type="search"
                placeholder="Search employee or employer…"
                class="pl-9"
            />
        </div>

        <ul v-if="filteredCases.length > 0" class="flex flex-col gap-2">
            <li
                v-for="medicalCase in filteredCases"
                :key="medicalCase.id"
                class="flex items-center gap-4 rounded-lg border p-4 transition-colors hover:bg-muted/50"
            >
                <div class="min-w-0 flex-1">
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="font-medium">
                            {{ medicalCase.employee_first_name }}
                            {{ medicalCase.employee_last_name }}
                        </span>
                        <CaseStatusBadge :status="medicalCase.status" />
                    </div>
                    <p class="mt-0.5 text-sm text-muted-foreground">
                        {{ medicalCase.employer_name }} · opened
                        {{ formatDate(medicalCase.opened_at) }}
                        <template v-if="medicalCase.expected_return_date">
                            · expected return
                            {{ formatDate(medicalCase.expected_return_date) }}
                        </template>
                    </p>
                </div>
                <Button
                    variant="ghost"
                    size="icon"
                    class="shrink-0"
                    aria-label="Edit medical case"
                    @click="editingCase = medicalCase"
                >
                    <Pencil class="size-4" />
                </Button>
            </li>
        </ul>

        <div
            v-else-if="medicalCases.length > 0"
            class="flex flex-col items-center gap-2 rounded-lg border border-dashed py-12 text-center"
        >
            <Search class="size-8 text-muted-foreground" />
            <p class="font-medium">No matching medical cases</p>
            <p class="text-sm text-muted-foreground">
                Try a different search term.
            </p>
        </div>

        <div
            v-else
            class="flex flex-col items-center gap-3 rounded-lg border border-dashed py-16 text-center"
        >
            <div
                class="flex size-12 items-center justify-center rounded-full bg-muted"
            >
                <Stethoscope class="size-6 text-muted-foreground" />
            </div>
            <div>
                <p class="font-medium">No medical cases yet</p>
                <p class="mt-1 text-sm text-muted-foreground">
                    {{
                        openCases.length > 0
                            ? 'Attach a medical dossier to an open case.'
                            : 'Medical cases appear once Case Officers open absence cases.'
                    }}
                </p>
            </div>
            <Button
                v-if="openCases.length > 0"
                variant="outline"
                @click="showCreateDialog = true"
            >
                <Plus class="size-4" />
                Add medical case
            </Button>
        </div>
    </div>

    <Dialog v-model:open="showCreateDialog">
        <DialogContent class="max-h-[85vh] overflow-y-auto">
            <DialogHeader>
                <DialogTitle>Add medical case</DialogTitle>
            </DialogHeader>
            <Form
                v-bind="store.form()"
                v-slot="{ errors, processing }"
                :reset-on-success="[
                    'diagnosis_notes',
                    'restrictions',
                    'advice',
                    'expected_return_date',
                ]"
                class="space-y-4"
                @success="showCreateDialog = false"
            >
                <div class="grid gap-2">
                    <Label for="case_id">Case</Label>
                    <select
                        id="case_id"
                        name="case_id"
                        required
                        class="h-9 rounded-md border border-input bg-transparent px-3 text-sm shadow-xs"
                    >
                        <option value="" disabled selected>
                            Select an open case
                        </option>
                        <option
                            v-for="openCase in openCases"
                            :key="openCase.id"
                            :value="openCase.id"
                        >
                            {{ openCase.employee.first_name }}
                            {{ openCase.employee.last_name }} —
                            {{ openCase.employer.name }}
                        </option>
                    </select>
                    <InputError :message="errors.case_id" />
                </div>
                <div class="grid gap-2">
                    <Label for="diagnosis_notes">Diagnosis notes</Label>
                    <Textarea
                        id="diagnosis_notes"
                        name="diagnosis_notes"
                        placeholder="Stays in Doctors only"
                    />
                    <InputError :message="errors.diagnosis_notes" />
                </div>
                <div class="grid gap-2">
                    <Label for="restrictions">Restrictions</Label>
                    <Textarea
                        id="restrictions"
                        name="restrictions"
                        placeholder="Shared with Case Officers"
                    />
                    <InputError :message="errors.restrictions" />
                </div>
                <div class="grid gap-2">
                    <Label for="advice">Advice</Label>
                    <Textarea
                        id="advice"
                        name="advice"
                        placeholder="Shared with Case Officers"
                    />
                    <InputError :message="errors.advice" />
                </div>
                <div class="grid gap-2">
                    <Label for="expected_return_date">
                        Expected return date
                    </Label>
                    <Input
                        id="expected_return_date"
                        type="date"
                        name="expected_return_date"
                    />
                    <InputError :message="errors.expected_return_date" />
                </div>
                <Button type="submit" :disabled="processing">
                    Add medical case
                </Button>
            </Form>
        </DialogContent>
    </Dialog>

    <Dialog
        :open="editingCase !== null"
        @update:open="
            (open) => {
                if (!open) editingCase = null;
            }
        "
    >
        <DialogContent v-if="editingCase" class="max-h-[85vh] overflow-y-auto">
            <DialogHeader>
                <DialogTitle>
                    {{ editingCase.employee_first_name }}
                    {{ editingCase.employee_last_name }}
                </DialogTitle>
            </DialogHeader>
            <Form
                v-bind="update.form({ medicalCase: editingCase.id })"
                v-slot="{ errors, processing }"
                class="space-y-4"
                @success="editingCase = null"
            >
                <div class="grid gap-2">
                    <Label for="edit_diagnosis_notes">Diagnosis notes</Label>
                    <Textarea
                        id="edit_diagnosis_notes"
                        name="diagnosis_notes"
                        :default-value="
                            editingCase.diagnosis_notes ?? undefined
                        "
                        placeholder="Stays in Doctors only"
                    />
                    <InputError :message="errors.diagnosis_notes" />
                </div>
                <div class="grid gap-2">
                    <Label for="edit_restrictions">Restrictions</Label>
                    <Textarea
                        id="edit_restrictions"
                        name="restrictions"
                        :default-value="editingCase.restrictions ?? undefined"
                        placeholder="Shared with Case Officers"
                    />
                    <InputError :message="errors.restrictions" />
                </div>
                <div class="grid gap-2">
                    <Label for="edit_advice">Advice</Label>
                    <Textarea
                        id="edit_advice"
                        name="advice"
                        :default-value="editingCase.advice ?? undefined"
                        placeholder="Shared with Case Officers"
                    />
                    <InputError :message="errors.advice" />
                </div>
                <div class="grid gap-2">
                    <Label for="edit_expected_return_date">
                        Expected return date
                    </Label>
                    <Input
                        id="edit_expected_return_date"
                        type="date"
                        name="expected_return_date"
                        :default-value="
                            editingCase.expected_return_date ?? undefined
                        "
                    />
                    <InputError :message="errors.expected_return_date" />
                </div>
                <div class="grid gap-2">
                    <Label for="edit_status">Status</Label>
                    <select
                        id="edit_status"
                        name="status"
                        required
                        class="h-9 rounded-md border border-input bg-transparent px-3 text-sm shadow-xs"
                    >
                        <option
                            value="open"
                            :selected="editingCase.status === 'open'"
                        >
                            Open
                        </option>
                        <option
                            value="closed"
                            :selected="editingCase.status === 'closed'"
                        >
                            Closed
                        </option>
                    </select>
                    <InputError :message="errors.status" />
                </div>
                <Button type="submit" :disabled="processing">
                    Save changes
                </Button>
            </Form>
        </DialogContent>
    </Dialog>
</template>
