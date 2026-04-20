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
        yellow: 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200',
        blue: 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200',
        indigo: 'bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-200',
        green: 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
        red: 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200',
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

    <div class="flex flex-col gap-6 p-4">
        <div class="flex items-center justify-between">
            <h1 class="text-xl font-semibold tracking-tight">Orders</h1>
            <Button as-child>
                <Link :href="ordersCreate.url()">
                    <Plus class="mr-1 size-4" />
                    New Order
                </Link>
            </Button>
        </div>

        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div class="flex flex-wrap gap-2">
                <Button
                    size="sm"
                    :variant="!currentStatus ? 'default' : 'outline'"
                    @click="filterByStatus(null)"
                >
                    All
                </Button>
                <Button
                    v-for="s in statuses"
                    :key="s.value"
                    size="sm"
                    :variant="currentStatus === s.value ? 'default' : 'outline'"
                    @click="filterByStatus(s.value)"
                >
                    {{ s.label }}
                </Button>
            </div>
            <div class="relative w-full sm:w-64">
                <Search class="absolute left-2.5 top-2.5 size-4 text-muted-foreground" />
                <Input
                    v-model="search"
                    placeholder="Search name, phone, address..."
                    class="pl-9"
                />
            </div>
        </div>

        <div class="overflow-x-auto rounded-lg border">
            <table class="w-full text-sm">
                <thead class="border-b bg-muted/50">
                    <tr>
                        <th class="px-4 py-3 text-left font-medium">Code</th>
                        <th class="px-4 py-3 text-left font-medium">
                            Customer
                        </th>
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
                        class="border-b transition-colors hover:bg-muted/50"
                    >
                        <td class="px-4 py-3">
                            <Link
                                :href="
                                    OrderController.show.url({
                                        order: order.id,
                                    })
                                "
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
                        <td class="px-4 py-3">{{ order.item_description }}</td>
                        <td class="px-4 py-3 text-right">
                            {{ order.quantity }}
                        </td>
                        <td class="px-4 py-3 text-right font-mono">
                            {{ Number(order.total_price).toLocaleString() }}
                        </td>
                        <td class="px-4 py-3">
                            <Badge
                                variant="secondary"
                                :class="
                                    statusColor(statusLabel(order.status).color)
                                "
                            >
                                {{ statusLabel(order.status).label }}
                            </Badge>
                        </td>
                        <td class="px-4 py-3 text-muted-foreground">
                            {{
                                new Date(
                                    order.created_at,
                                ).toLocaleDateString()
                            }}
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
                                        <Form
                                            v-bind="OrderController.destroy.form({ order: order.id })"
                                            v-slot="{ processing }"
                                        >
                                            <DialogHeader class="space-y-3">
                                                <DialogTitle>Delete this order?</DialogTitle>
                                                <DialogDescription>
                                                    Order <span class="font-mono font-medium">{{ order.order_code }}</span>
                                                    will be permanently deleted.
                                                </DialogDescription>
                                            </DialogHeader>
                                            <DialogFooter class="mt-6 gap-2">
                                                <DialogClose as-child>
                                                    <Button variant="secondary">Cancel</Button>
                                                </DialogClose>
                                                <Button
                                                    type="submit"
                                                    variant="destructive"
                                                    :disabled="processing"
                                                >
                                                    Delete
                                                </Button>
                                            </DialogFooter>
                                        </Form>
                                    </DialogContent>
                                </Dialog>
                            </div>
                        </td>
                    </tr>
                    <tr v-if="orders.data.length === 0">
                        <td
                            colspan="8"
                            class="px-4 py-8 text-center text-muted-foreground"
                        >
                            No orders found.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div
            v-if="orders.links.length > 3"
            class="flex justify-center gap-1"
        >
            <template v-for="link in orders.links" :key="link.label">
                <Link
                    v-if="link.url"
                    :href="link.url"
                    class="rounded-md border px-3 py-1 text-sm transition-colors hover:bg-muted"
                    :class="{ 'bg-primary text-primary-foreground': link.active }"
                    v-html="link.label"
                />
                <span
                    v-else
                    class="rounded-md border px-3 py-1 text-sm text-muted-foreground opacity-50"
                    v-html="link.label"
                />
            </template>
        </div>
    </div>
</template>
