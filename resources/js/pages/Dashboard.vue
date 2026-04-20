<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { Badge } from '@/components/ui/badge';
import { dashboard } from '@/routes';
import { index as ordersIndex } from '@/routes/orders';

type StatusCount = {
    value: string;
    label: string;
    color: string;
    count: number;
};

type Props = {
    statusCounts: StatusCount[];
};

defineProps<Props>();

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'Dashboard',
                href: dashboard(),
            },
        ],
    },
});

function statusBg(color: string): string {
    const map: Record<string, string> = {
        yellow: 'border-yellow-200 bg-yellow-50 dark:border-yellow-800 dark:bg-yellow-950',
        blue: 'border-blue-200 bg-blue-50 dark:border-blue-800 dark:bg-blue-950',
        indigo: 'border-indigo-200 bg-indigo-50 dark:border-indigo-800 dark:bg-indigo-950',
        green: 'border-green-200 bg-green-50 dark:border-green-800 dark:bg-green-950',
        red: 'border-red-200 bg-red-50 dark:border-red-800 dark:bg-red-950',
    };
    return map[color] ?? '';
}

function badgeColor(color: string): string {
    const map: Record<string, string> = {
        yellow: 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200',
        blue: 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200',
        indigo: 'bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-200',
        green: 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
        red: 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200',
    };
    return map[color] ?? '';
}
</script>

<template>
    <Head title="Dashboard" />

    <div class="flex flex-col gap-6 p-4">
        <div class="flex items-center justify-between">
            <h1 class="text-xl font-semibold tracking-tight">Dashboard</h1>
        </div>

        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-5">
            <Link
                v-for="sc in statusCounts"
                :key="sc.value"
                :href="ordersIndex.url({ query: { status: sc.value } })"
                class="rounded-xl border p-4 transition-colors hover:bg-muted/50"
                :class="statusBg(sc.color)"
            >
                <div class="flex items-center justify-between">
                    <Badge variant="secondary" :class="badgeColor(sc.color)">
                        {{ sc.label }}
                    </Badge>
                </div>
                <p class="mt-3 text-3xl font-bold tracking-tight">
                    {{ sc.count }}
                </p>
            </Link>
        </div>
    </div>
</template>
