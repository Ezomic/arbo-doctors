<script setup lang="ts">
import { Head, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import Heading from '@/components/Heading.vue';
import { edit } from '@/routes/profile';

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'Profile',
                href: edit(),
            },
        ],
    },
});

const page = usePage();
const user = computed(() => page.props.auth.user);
</script>

<template>
    <Head title="Profile" />

    <h1 class="sr-only">Profile</h1>

    <div class="flex flex-col space-y-6">
        <Heading
            variant="small"
            title="Profile"
            description="Synced from Identity on login — edit these in Identity, not here"
        />

        <dl class="space-y-4 text-sm">
            <div>
                <dt class="text-muted-foreground">Name</dt>
                <dd class="font-medium">{{ user.name }}</dd>
            </div>
            <div>
                <dt class="text-muted-foreground">Email</dt>
                <dd class="font-medium">{{ user.email }}</dd>
            </div>
            <div>
                <dt class="text-muted-foreground">Role</dt>
                <dd class="font-medium">{{ user.current_role }}</dd>
            </div>
            <div>
                <dt class="text-muted-foreground">Tenant</dt>
                <dd class="font-medium">{{ user.tenant_id }}</dd>
            </div>
        </dl>
    </div>
</template>
