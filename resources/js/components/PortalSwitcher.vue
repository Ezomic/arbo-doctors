<script setup lang="ts">
import { usePage } from '@inertiajs/vue3';
import { Grid2X2 } from '@lucide/vue';
import { computed, ref } from 'vue';
import { Button } from '@/components/ui/button';
import { Dialog, DialogContent, DialogHeader, DialogTitle } from '@/components/ui/dialog';

type AccessibleApp = { slug: string; name: string; base_url: string; as: string };

// Plain <a> tags on purpose, not Inertia's <Link>: these are cross-origin
// destinations, and Inertia's <Link> navigates via fetch(), which the
// browser blocks with a CORS error for a cross-origin request initiated
// from script. A real anchor tag is a top-level navigation and isn't
// subject to CORS at all.
const dotColor: Record<string, string> = {
    identity: 'bg-violet-500',
    'case-officers': 'bg-emerald-500',
    employers: 'bg-blue-500',
    doctors: 'bg-fuchsia-500',
    employees: 'bg-amber-500',
    admin: 'bg-slate-500',
};

const page = usePage<{ auth: { user: { accessible_apps: AccessibleApp[] | null } } }>();

const currentSlug = 'doctors';

const otherApps = computed(() =>
    (page.props.auth.user.accessible_apps ?? []).filter((app) => app.slug !== currentSlug),
);

const showDialog = ref(false);
</script>

<template>
    <Button
        v-if="otherApps.length > 0"
        variant="outline"
        size="icon"
        aria-label="Switch portal"
        @click="showDialog = true"
    >
        <Grid2X2 class="size-4" />
    </Button>

    <Dialog v-model:open="showDialog">
        <DialogContent>
            <DialogHeader>
                <DialogTitle>Switch portal</DialogTitle>
            </DialogHeader>
            <div class="flex flex-col gap-1">
                <a
                    v-for="app in otherApps"
                    :key="app.slug"
                    :href="`${app.base_url}/login`"
                    class="flex items-center gap-3 rounded-md px-3 py-2 text-sm hover:bg-muted"
                >
                    <span class="size-2.5 shrink-0 rounded-full" :class="dotColor[app.slug] ?? 'bg-gray-400'" />
                    <span class="flex flex-col leading-tight">
                        <span>{{ app.name }}</span>
                        <span class="text-xs text-muted-foreground">as {{ app.as }}</span>
                    </span>
                </a>
            </div>
        </DialogContent>
    </Dialog>
</template>
