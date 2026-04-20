<script setup lang="ts">
import { Form, Head, Link, router } from '@inertiajs/vue3';
import { ref, watch } from 'vue';
import OrderController from '@/actions/App/Http/Controllers/OrderController';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import {
    Dialog,
    DialogClose,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
    DialogTrigger,
} from '@/components/ui/dialog';
import { Input } from '@/components/ui/input';
import { index as ordersIndex, create as ordersCreate } from '@/routes/orders';
import { Pencil, Plus, Search, Trash2 } from 'lucide-vue-next';

type Status = {
    value: string;
    label: string;
    color: string;
};

type Order = {
    id: number;
    order_code: string;
    customer_name: string;
    customer_phone: string;
    item_description: string;
    quantity: number;
    total_price: string;
    status: string;
    created_at: string;
    creator: { id: number; name: string };
};

type Props = {
    orders: {
        data: Order[];
        links: { url: string | null; label: string; active: boolean }[];
    };
    statuses: Status[];
    currentStatus: string | null;
    currentSearch: string | null;
};

const props = defineProps<Props>();

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'Orders',
                href: ordersIndex.url(),
            },
        ],
    },
});

const search = ref(props.currentSearch ?? '');
let debounceTimer: ReturnType<typeof setTimeout>;

watch(search, (value) => {
    clearTimeout(debounceTimer);
    debounceTimer = setTimeout(() => {
        const params: Record<string, string> = {};
        if (props.currentStatus) params.status = props.currentStatus;
        if (value) params.search = value;
        router.get(ordersIndex.url(), params, { preserveState: true });
    }, 300);
});

function filterByStatus(status: string | null) {
    const params: Record<string, string> = {};
    if (status) params.status = status;
    if (search.value) params.search = search.value;
    router.get(ordersIndex.url(), params, { preserveState: true });
}

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

function statusLabel(statusValue: string): { label: string; color: string } {
    const s = props.statuses.find((st) => st.value === statusValue);
    return s ?? { label: statusValue, color: '' };
}
</script>

<template>
    <Head title="Orders" />

    <div class="flex flex-col gap-3 px-3 py-4 sm:gap-4 sm:px-4 lg:gap-6 lg:px-6 lg:py-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <h1 class="text-lg font-bold tracking-tight sm:text-xl lg:text-2xl">Orders</h1>
            <Button as-child class="h-10 shadow-sm shadow-primary/20 sm:h-9">
                <Link :href="ordersCreate.url()">
                    <Plus class="mr-1 size-4" />
                    New Order
                </Link>
            </Button>
        </div>

        <!-- Filters -->
        <div class="flex flex-col gap-2.5">
            <div class="relative">
                <Search class="absolute left-3 top-1/2 size-4 -translate-y-1/2 text-muted-foreground" />
                <Input
                    v-model="search"
                    placeholder="Search name, phone, address..."
                    class="h-11 pl-10 text-base sm:h-10 sm:text-sm"
                />
            </div>
            <div class="flex gap-1.5 overflow-x-auto pb-0.5">
                <Button
                    size="sm"
                    :variant="!currentStatus ? 'default' : 'outline'"
                    class="h-8 shrink-0 rounded-full px-3 text-xs"
                    @click="filterByStatus(null)"
                >
                    All
                </Button>
                <Button
                    v-for="s in statuses"
                    :key="s.value"
                    size="sm"
                    :variant="currentStatus === s.value ? 'default' : 'outline'"
                    class="h-8 shrink-0 rounded-full px-3 text-xs"
                    @click="filterByStatus(s.value)"
                >
                    {{ s.label }}
                </Button>
            </div>
        </div>

        <!-- Mobile card view -->
        <div class="flex flex-col gap-2 sm:hidden">
            <Link
                v-for="order in orders.data"
                :key="order.id"
                :href="OrderController.show.url({ order: order.id })"
                class="block rounded-xl border bg-card p-3.5 transition-all active:scale-[0.98]"
            >
                <div class="flex items-start justify-between">
                    <span class="font-mono text-sm font-medium text-primary">
                        {{ order.order_code }}
                    </span>
                    <Badge variant="secondary" :class="statusColor(statusLabel(order.status).color)" class="text-xs">
                        {{ statusLabel(order.status).label }}
                    </Badge>
                </div>
                <div class="mt-1.5">
                    <p class="text-sm font-medium">{{ order.customer_name }}</p>
                    <p class="text-xs text-muted-foreground">{{ order.customer_phone }}</p>
                </div>
                <div class="mt-2 flex items-center justify-between border-t pt-2">
                    <div class="text-xs text-muted-foreground">
                        {{ order.item_description }} &middot;
                        <span class="font-mono font-medium text-foreground">{{ Number(order.total_price).toLocaleString() }}</span>
                    </div>
                    <div class="flex items-center gap-0.5" @click.prevent>
                        <Button variant="ghost" size="icon" class="size-9" as-child>
                            <Link :href="OrderController.edit.url({ order: order.id })">
                                <Pencil class="size-4" />
                            </Link>
                        </Button>
                        <Dialog>
                            <DialogTrigger as-child>
                                <Button variant="ghost" size="icon" class="size-9 text-destructive hover:text-destructive">
                                    <Trash2 class="size-4" />
                                </Button>
                            </DialogTrigger>
                            <DialogContent>
                                <Form v-bind="OrderController.destroy.form({ order: order.id })" v-slot="{ processing }">
                                    <DialogHeader class="space-y-3">
                                        <DialogTitle>Delete this order?</DialogTitle>
                                        <DialogDescription>
                                            Order <span class="font-mono font-medium">{{ order.order_code }}</span> will be permanently deleted.
                                        </DialogDescription>
                                    </DialogHeader>
                                    <DialogFooter class="mt-6 gap-2">
                                        <DialogClose as-child>
                                            <Button variant="secondary" class="h-11 sm:h-9">Cancel</Button>
                                        </DialogClose>
                                        <Button type="submit" variant="destructive" :disabled="processing" class="h-11 sm:h-9">Delete</Button>
                                    </DialogFooter>
                                </Form>
                            </DialogContent>
                        </Dialog>
                    </div>
                </div>
            </Link>
            <div v-if="orders.data.length === 0" class="rounded-xl border bg-card p-8 text-center text-sm text-muted-foreground">
                No orders found.
            </div>
        </div>

        <!-- Desktop table -->
        <div class="hidden overflow-hidden rounded-xl border bg-card sm:block">
            <table class="w-full text-sm">
                <thead class="border-b bg-muted/50">
                    <tr>
                        <th class="px-4 py-3 text-left font-medium">Code</th>
                        <th class="px-4 py-3 text-left font-medium">Customer</th>
                        <th class="px-4 py-3 text-left font-medium">Item</th>
                        <th class="px-4 py-3 text-right font-medium">Qty</th>
                        <th class="px-4 py-3 text-right font-medium">Total</th>
                        <th class="px-4 py-3 text-left font-medium">Status</th>
                        <th class="px-4 py-3 text-left font-medium">Date</th>
                        <th class="px-4 py-3 text-right font-medium">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr
                        v-for="order in orders.data"
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
                        <td class="px-4 py-3">{{ order.item_description }}</td>
                        <td class="px-4 py-3 text-right">{{ order.quantity }}</td>
                        <td class="px-4 py-3 text-right font-mono">{{ Number(order.total_price).toLocaleString() }}</td>
                        <td class="px-4 py-3">
                            <Badge variant="secondary" :class="statusColor(statusLabel(order.status).color)">
                                {{ statusLabel(order.status).label }}
                            </Badge>
                        </td>
                        <td class="px-4 py-3 text-muted-foreground">
                            {{ new Date(order.created_at).toLocaleDateString() }}
                        </td>
                        <td class="px-4 py-3 text-right">
                            <div class="flex items-center justify-end gap-1">
                                <Button variant="ghost" size="icon" class="size-8" as-child>
                                    <Link :href="OrderController.edit.url({ order: order.id })">
                                        <Pencil class="size-3.5" />
                                    </Link>
                                </Button>
                                <Dialog>
                                    <DialogTrigger as-child>
                                        <Button variant="ghost" size="icon" class="size-8 text-destructive hover:text-destructive">
                                            <Trash2 class="size-3.5" />
                                        </Button>
                                    </DialogTrigger>
                                    <DialogContent>
                                        <Form v-bind="OrderController.destroy.form({ order: order.id })" v-slot="{ processing }">
                                            <DialogHeader class="space-y-3">
                                                <DialogTitle>Delete this order?</DialogTitle>
                                                <DialogDescription>
                                                    Order <span class="font-mono font-medium">{{ order.order_code }}</span> will be permanently deleted.
                                                </DialogDescription>
                                            </DialogHeader>
                                            <DialogFooter class="mt-6 gap-2">
                                                <DialogClose as-child>
                                                    <Button variant="secondary">Cancel</Button>
                                                </DialogClose>
                                                <Button type="submit" variant="destructive" :disabled="processing">Delete</Button>
                                            </DialogFooter>
                                        </Form>
                                    </DialogContent>
                                </Dialog>
                            </div>
                        </td>
                    </tr>
                    <tr v-if="orders.data.length === 0">
                        <td colspan="8" class="px-4 py-8 text-center text-muted-foreground">No orders found.</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div v-if="orders.links.length > 3" class="flex justify-center gap-1">
            <template v-for="link in orders.links" :key="link.label">
                <Link
                    v-if="link.url"
                    :href="link.url"
                    class="rounded-lg border px-3 py-1.5 text-sm transition-colors hover:bg-accent"
                    :class="{ 'bg-primary text-primary-foreground border-primary': link.active }"
                    v-html="link.label"
                />
                <span
                    v-else
                    class="rounded-lg border px-3 py-1.5 text-sm text-muted-foreground opacity-50"
                    v-html="link.label"
                />
            </template>
        </div>
    </div>
</template>
