<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import OrderTrackController from '@/actions/App/Http/Controllers/OrderTrackController';
import { ArrowLeft } from 'lucide-vue-next';

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
        yellow: 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200',
        blue: 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200',
        indigo: 'bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-200',
        green: 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
        red: 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200',
    };
    return map[color] ?? '';
}

const steps = ['pending', 'approved', 'on_the_way', 'delivered'];

function stepIndex(status: string): number {
    if (status === 'returned') return -1;
    return steps.indexOf(status);
}
</script>

<template>
    <Head title="Order Status" />

    <div class="flex min-h-screen flex-col items-center justify-center bg-[#FDFDFC] p-6 dark:bg-[#0a0a0a]">
        <main class="w-full max-w-md">
            <div class="rounded-lg border bg-white p-8 shadow-sm dark:border-[#3E3E3A] dark:bg-[#161615]">
                <template v-if="order">
                    <div class="mb-6 text-center">
                        <p class="font-mono text-sm text-[#706f6c] dark:text-[#A1A09A]">
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

                    <div v-if="order.status !== 'returned'" class="mb-6">
                        <div class="flex items-center justify-between">
                            <div
                                v-for="(step, i) in ['Pending', 'Approved', 'On the Way', 'Delivered']"
                                :key="step"
                                class="flex flex-1 flex-col items-center"
                            >
                                <div
                                    class="flex size-8 items-center justify-center rounded-full border-2 text-xs font-bold transition-colors"
                                    :class="i <= stepIndex(order.status)
                                        ? 'border-primary bg-primary text-primary-foreground'
                                        : 'border-muted-foreground/30 text-muted-foreground/50'"
                                >
                                    {{ i + 1 }}
                                </div>
                                <span class="mt-1 text-[10px] text-muted-foreground">{{ step }}</span>
                            </div>
                        </div>
                    </div>

                    <div v-else class="mb-6 rounded-md bg-red-50 p-3 text-center text-sm text-red-700 dark:bg-red-950 dark:text-red-300">
                        This order has been returned.
                    </div>

                    <dl class="space-y-3 text-sm">
                        <div class="flex justify-between">
                            <dt class="text-[#706f6c] dark:text-[#A1A09A]">Customer</dt>
                            <dd class="font-medium text-[#1b1b18] dark:text-[#EDEDEC]">{{ order.customer_name }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-[#706f6c] dark:text-[#A1A09A]">Item</dt>
                            <dd class="font-medium text-[#1b1b18] dark:text-[#EDEDEC]">{{ order.item_description }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-[#706f6c] dark:text-[#A1A09A]">Quantity</dt>
                            <dd class="font-medium text-[#1b1b18] dark:text-[#EDEDEC]">{{ order.quantity }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-[#706f6c] dark:text-[#A1A09A]">Total</dt>
                            <dd class="font-mono font-medium text-[#1b1b18] dark:text-[#EDEDEC]">{{ Number(order.total_price).toLocaleString() }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-[#706f6c] dark:text-[#A1A09A]">Order Date</dt>
                            <dd class="font-medium text-[#1b1b18] dark:text-[#EDEDEC]">{{ new Date(order.created_at).toLocaleDateString() }}</dd>
                        </div>
                    </dl>
                </template>

                <template v-else>
                    <div class="py-8 text-center">
                        <p class="text-lg font-medium text-[#1b1b18] dark:text-[#EDEDEC]">Order not found</p>
                        <p class="mt-2 text-sm text-[#706f6c] dark:text-[#A1A09A]">
                            Please check your order code and try again.
                        </p>
                    </div>
                </template>

                <div class="mt-6">
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
