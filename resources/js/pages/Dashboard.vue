<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import OrderController from '@/actions/App/Http/Controllers/OrderController';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { dashboard } from '@/routes';
import { index as ordersIndex } from '@/routes/orders';
import { ArrowRight } from 'lucide-vue-next';

type StatusCount = {
    value: string;
    label: string;
    color: string;
    count: number;
};

type Order = {
    id: number;
    order_code: string;
    customer_name: string;
    customer_phone: string;
    status: string;
    created_at: string;
    creator: { id: number; name: string };
};

type Props = {
    statusCounts: StatusCount[];
    recentOrders: Order[];
};

const props = defineProps<Props>();

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

function statusInfo(statusValue: string): StatusCount {
    return (
        props.statusCounts.find((s) => s.value === statusValue) ?? {
            value: statusValue,
            label: statusValue,
            color: '',
            count: 0,
        }
    );
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

        <div class="space-y-3">
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-semibold tracking-tight">Recent Orders</h2>
                <Button variant="ghost" size="sm" as-child>
                    <Link :href="ordersIndex.url()">
                        View All
                        <ArrowRight class="ml-1 size-4" />
                    </Link>
                </Button>
            </div>

            <div class="overflow-x-auto rounded-lg border">
                <table class="w-full text-sm">
                    <thead class="border-b bg-muted/50">
                        <tr>
                            <th class="px-4 py-3 text-left font-medium">Code</th>
                            <th class="px-4 py-3 text-left font-medium">Customer</th>
                            <th class="px-4 py-3 text-left font-medium">Status</th>
                            <th class="px-4 py-3 text-left font-medium">Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="order in recentOrders"
                            :key="order.id"
                            class="border-b transition-colors hover:bg-muted/50"
                        >
                            <td class="px-4 py-3">
                                <Link
                                    :href="OrderController.show.url({ order: order.id })"
                                    class="font-mono text-sm font-medium text-primary underline-offset-4 hover:underline"
                                >
                                    {{ order.order_code }}
                                </Link>
                            </td>
                            <td class="px-4 py-3">
                                <div>{{ order.customer_name }}</div>
                                <div class="text-xs text-muted-foreground">
                                    {{ order.customer_phone }}
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                <Badge
                                    variant="secondary"
                                    :class="badgeColor(statusInfo(order.status).color)"
                                >
                                    {{ statusInfo(order.status).label }}
                                </Badge>
                            </td>
                            <td class="px-4 py-3 text-muted-foreground">
                                {{ new Date(order.created_at).toLocaleDateString() }}
                            </td>
                        </tr>
                        <tr v-if="recentOrders.length === 0">
                            <td
                                colspan="4"
                                class="px-4 py-8 text-center text-muted-foreground"
                            >
                                No orders yet.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>
