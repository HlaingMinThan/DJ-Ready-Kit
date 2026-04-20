<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import OrderController from '@/actions/App/Http/Controllers/OrderController';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { dashboard } from '@/routes';
import { index as ordersIndex } from '@/routes/orders';
import { ArrowRight, Package, TrendingUp } from 'lucide-vue-next';

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

const totalOrders = props.statusCounts.reduce((sum, s) => sum + s.count, 0);

function statusBg(color: string): string {
    const map: Record<string, string> = {
        yellow: 'border-yellow-300/40 bg-yellow-50 dark:border-yellow-700/40 dark:bg-yellow-950/50',
        blue: 'border-blue-300/40 bg-blue-50 dark:border-blue-700/40 dark:bg-blue-950/50',
        indigo: 'border-indigo-300/40 bg-indigo-50 dark:border-indigo-700/40 dark:bg-indigo-950/50',
        green: 'border-green-300/40 bg-green-50 dark:border-green-700/40 dark:bg-green-950/50',
        red: 'border-red-300/40 bg-red-50 dark:border-red-700/40 dark:bg-red-950/50',
    };
    return map[color] ?? '';
}

function badgeColor(color: string): string {
    const map: Record<string, string> = {
        yellow: 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/80 dark:text-yellow-200',
        blue: 'bg-blue-100 text-blue-800 dark:bg-blue-900/80 dark:text-blue-200',
        indigo: 'bg-indigo-100 text-indigo-800 dark:bg-indigo-900/80 dark:text-indigo-200',
        green: 'bg-green-100 text-green-800 dark:bg-green-900/80 dark:text-green-200',
        red: 'bg-red-100 text-red-800 dark:bg-red-900/80 dark:text-red-200',
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

    <div class="flex flex-col gap-4 px-3 py-4 sm:gap-6 sm:px-4 lg:px-6 lg:py-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-lg font-bold tracking-tight sm:text-xl lg:text-2xl">Dashboard</h1>
                <p class="mt-0.5 text-sm text-muted-foreground">
                    <TrendingUp class="mr-1 inline size-3.5" />
                    {{ totalOrders }} total orders
                </p>
            </div>
            <Button as-child size="sm" class="shadow-sm shadow-primary/20">
                <Link :href="ordersIndex.url()">
                    View All
                    <ArrowRight class="ml-1 size-4" />
                </Link>
            </Button>
        </div>

        <!-- Status cards -->
        <div class="flex gap-2.5 overflow-x-auto pb-1 sm:grid sm:grid-cols-3 sm:gap-3 sm:overflow-visible lg:grid-cols-5">
            <Link
                v-for="sc in statusCounts"
                :key="sc.value"
                :href="ordersIndex.url({ query: { status: sc.value } })"
                class="flex min-w-[120px] flex-col rounded-xl border p-3 transition-all hover:shadow-md active:scale-[0.98] sm:min-w-0 sm:p-4"
                :class="statusBg(sc.color)"
            >
                <Badge variant="secondary" :class="badgeColor(sc.color)" class="w-fit text-xs">
                    {{ sc.label }}
                </Badge>
                <p class="mt-2 text-xl font-bold tracking-tight sm:mt-3 sm:text-2xl lg:text-3xl">
                    {{ sc.count }}
                </p>
            </Link>
        </div>

        <!-- Recent orders -->
        <div class="space-y-3">
            <div class="flex items-center gap-2">
                <Package class="size-4 text-muted-foreground" />
                <h2 class="text-base font-semibold tracking-tight">Recent Orders</h2>
            </div>

            <!-- Mobile card view -->
            <div class="flex flex-col gap-2 sm:hidden">
                <Link
                    v-for="order in recentOrders"
                    :key="order.id"
                    :href="OrderController.show.url({ order: order.id })"
                    class="flex items-center justify-between rounded-xl border bg-card p-3.5 transition-all active:scale-[0.98]"
                >
                    <div class="min-w-0 flex-1">
                        <p class="font-mono text-sm font-medium text-primary">
                            {{ order.order_code }}
                        </p>
                        <p class="mt-0.5 truncate text-sm">{{ order.customer_name }}</p>
                        <p class="text-xs text-muted-foreground">
                            {{ new Date(order.created_at).toLocaleDateString() }}
                        </p>
                    </div>
                    <Badge
                        variant="secondary"
                        :class="badgeColor(statusInfo(order.status).color)"
                        class="ml-3 shrink-0 text-xs"
                    >
                        {{ statusInfo(order.status).label }}
                    </Badge>
                </Link>
                <div v-if="recentOrders.length === 0" class="rounded-xl border bg-card p-8 text-center text-sm text-muted-foreground">
                    No orders yet.
                </div>
            </div>

            <!-- Desktop table -->
            <div class="hidden overflow-hidden rounded-xl border bg-card sm:block">
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
                            class="border-b last:border-0 transition-colors hover:bg-muted/50"
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
                                <div class="text-xs text-muted-foreground">{{ order.customer_phone }}</div>
                            </td>
                            <td class="px-4 py-3">
                                <Badge variant="secondary" :class="badgeColor(statusInfo(order.status).color)">
                                    {{ statusInfo(order.status).label }}
                                </Badge>
                            </td>
                            <td class="px-4 py-3 text-muted-foreground">
                                {{ new Date(order.created_at).toLocaleDateString() }}
                            </td>
                        </tr>
                        <tr v-if="recentOrders.length === 0">
                            <td colspan="4" class="px-4 py-8 text-center text-muted-foreground">No orders yet.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>
