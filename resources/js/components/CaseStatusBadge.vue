<script setup lang="ts">
import { Badge } from '@/components/ui/badge';

defineProps<{
    status: string;
}>();

const statusClasses: Record<string, string> = {
    open: 'border-transparent bg-emerald-100 text-emerald-800 dark:bg-emerald-500/15 dark:text-emerald-400',
    closed: 'border-transparent bg-muted text-muted-foreground',
};
</script>

<template>
    <Badge
        variant="outline"
        class="capitalize"
        :class="statusClasses[status] ?? ''"
    >
        {{ status }}
    </Badge>
</template>
