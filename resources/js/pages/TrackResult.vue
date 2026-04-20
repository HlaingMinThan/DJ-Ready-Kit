<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import AppLogoIcon from '@/components/AppLogoIcon.vue';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { dashboard } from '@/routes';
import OrderTrackController from '@/actions/App/Http/Controllers/OrderTrackController';
import { ArrowLeft, Check, Search } from 'lucide-vue-next';

type TrackedOrder = {
    order_code: string;
    customer_name: string;
    item_description: string;
    quantity: number;
    total_price: string;
    status: string;
    status_label: string;
    status_color: string;
    created_at: string;
};

type Props = {
    order: TrackedOrder | null;
};

defineProps<Props>();

defineOptions({ layout: false });

function statusColor(color: string): string {
    const map: Record<string, string> = {
        yellow: 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/80 dark:text-yellow-200',
        blue: 'bg-blue-100 text-blue-800 dark:bg-blue-900/80 dark:text-blue-200',
        indigo: 'bg-indigo-100 text-indigo-800 dark:bg-indigo-900/80 dark:text-indigo-200',
        green: 'bg-green-100 text-green-800 dark:bg-green-900/80 dark:text-green-200',
        red: 'bg-red-100 text-red-800 dark:bg-red-900/80 dark:text-red-200',
    };
    return map[color] ?? '';
}

const steps = ['pending', 'approved', 'on_the_way', 'delivered'];
const stepLabels = ['Pending', 'Approved', 'On the Way', 'Delivered'];

function stepIndex(status: string): number {
    if (status === 'returned') return -1;
    return steps.indexOf(status);
}
</script>

<template>
    <Head title="Order Status" />

    <div class="flex min-h-screen flex-col bg-background">
        <!-- Nav bar -->
        <header class="border-b border-border/50 px-5 py-3 sm:px-8">
            <nav class="mx-auto flex max-w-5xl items-center justify-between">
                <Link
                    href="/"
                    class="group flex items-center gap-2.5 rounded-xl px-2 py-1.5 transition-colors hover:bg-accent"
                >
                    <ArrowLeft class="size-4 text-muted-foreground transition-transform group-hover:-translate-x-0.5" />
                    <div class="flex items-center gap-2">
                        <div class="flex size-7 items-center justify-center rounded-lg bg-primary text-primary-foreground">
                            <AppLogoIcon class="size-4" />
                        </div>
                        <span class="text-sm font-semibold">DJ Ready Kit</span>
                    </div>
                </Link>
                <Link
                    v-if="$page.props.auth?.user"
                    :href="dashboard()"
                    class="rounded-lg bg-primary px-4 py-2 text-sm font-medium text-primary-foreground transition-colors hover:bg-primary/90"
                >
                    Dashboard
                </Link>
            </nav>
        </header>

        <main class="flex w-full max-w-md flex-1 flex-col justify-center self-center px-6 py-10">
            <div class="overflow-hidden rounded-2xl border bg-card shadow-lg">
                <template v-if="order">
                    <!-- Status header -->
                    <div class="bg-gradient-to-br from-primary/10 via-primary/5 to-transparent p-6 text-center">
                        <p class="font-mono text-sm text-muted-foreground">
                            {{ order.order_code }}
                        </p>
                        <Badge
                            variant="secondary"
                            :class="statusColor(order.status_color)"
                            class="mt-2 text-base px-4 py-1"
                        >
                            {{ order.status_label }}
                        </Badge>
                    </div>

                    <div class="border-t p-6">
                        <!-- Progress steps -->
                        <div v-if="order.status !== 'returned'" class="mb-6">
                            <div class="flex items-center justify-between">
                                <div
                                    v-for="(label, i) in stepLabels"
                                    :key="label"
                                    class="flex flex-1 flex-col items-center"
                                >
                                    <div
                                        class="flex size-9 items-center justify-center rounded-full border-2 text-xs font-bold transition-colors"
                                        :class="i <= stepIndex(order.status)
                                            ? 'border-primary bg-primary text-primary-foreground shadow-sm shadow-primary/30'
                                            : 'border-muted text-muted-foreground/50'"
                                    >
                                        <Check v-if="i < stepIndex(order.status)" class="size-4" />
                                        <span v-else>{{ i + 1 }}</span>
                                    </div>
                                    <span class="mt-1.5 text-[10px] font-medium text-muted-foreground">{{ label }}</span>
                                </div>
                            </div>
                        </div>

                        <div v-else class="mb-6 rounded-xl bg-red-50 p-3 text-center text-sm text-red-700 dark:bg-red-950/50 dark:text-red-300">
                            This order has been returned.
                        </div>

                        <!-- Order details -->
                        <dl class="space-y-3 text-sm">
                            <div class="flex justify-between rounded-lg bg-muted/50 px-3 py-2">
                                <dt class="text-muted-foreground">Customer</dt>
                                <dd class="font-medium">{{ order.customer_name }}</dd>
                            </div>
                            <div class="flex justify-between rounded-lg bg-muted/50 px-3 py-2">
                                <dt class="text-muted-foreground">Item</dt>
                                <dd class="font-medium">{{ order.item_description }}</dd>
                            </div>
                            <div class="flex justify-between rounded-lg bg-muted/50 px-3 py-2">
                                <dt class="text-muted-foreground">Quantity</dt>
                                <dd class="font-medium">{{ order.quantity }}</dd>
                            </div>
                            <div class="flex justify-between rounded-lg bg-muted/50 px-3 py-2">
                                <dt class="text-muted-foreground">Total</dt>
                                <dd class="font-mono font-medium">{{ Number(order.total_price).toLocaleString() }}</dd>
                            </div>
                            <div class="flex justify-between rounded-lg bg-muted/50 px-3 py-2">
                                <dt class="text-muted-foreground">Order Date</dt>
                                <dd class="font-medium">{{ new Date(order.created_at).toLocaleDateString() }}</dd>
                            </div>
                        </dl>
                    </div>
                </template>

                <template v-else>
                    <div class="p-8 text-center">
                        <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-2xl bg-muted">
                            <Search class="size-6 text-muted-foreground" />
                        </div>
                        <p class="text-lg font-semibold">Order not found</p>
                        <p class="mt-1 text-sm text-muted-foreground">
                            Please check your order code and try again.
                        </p>
                    </div>
                </template>

                <div class="border-t p-6">
                    <Button variant="outline" as-child class="w-full">
                        <Link :href="OrderTrackController.index.url()">
                            <ArrowLeft class="mr-2 size-4" />
                            Track Another Order
                        </Link>
                    </Button>
                </div>
            </div>
        </main>
    </div>
</template>
