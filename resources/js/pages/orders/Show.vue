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
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import { index as ordersIndex } from '@/routes/orders';
import { ArrowLeft, MapPin, Package, Pencil, Phone, User } from 'lucide-vue-next';

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
    customer_address: string;
    item_description: string;
    quantity: number;
    total_price: string;
    status: string;
    notes: string | null;
    created_at: string;
    updated_at: string;
    creator: { id: number; name: string };
};

type Props = {
    order: Order;
    statuses: Status[];
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

const selectedStatus = ref(props.order.status);
const updatingStatus = ref(false);

watch(selectedStatus, (newStatus) => {
    if (newStatus === props.order.status) return;
    updatingStatus.value = true;
    router.patch(
        OrderController.update.url({ order: props.order.id }),
        { status: newStatus },
        {
            preserveScroll: true,
            onFinish: () => {
                updatingStatus.value = false;
            },
        },
    );
});

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

function currentStatusInfo(): Status {
    return (
        props.statuses.find((s) => s.value === props.order.status) ?? {
            value: props.order.status,
            label: props.order.status,
            color: '',
        }
    );
}
</script>

<template>
    <Head :title="`Order ${order.order_code}`" />

    <div class="mx-auto w-full max-w-2xl space-y-3 px-3 py-4 sm:space-y-4 sm:px-4 lg:space-y-6 lg:px-6 lg:py-6">
        <!-- Header -->
        <div class="flex items-start justify-between gap-3">
            <div class="min-w-0">
                <div class="flex items-center gap-2">
                    <Button variant="ghost" size="icon" class="size-9 shrink-0 sm:hidden" as-child>
                        <Link :href="ordersIndex.url()">
                            <ArrowLeft class="size-4" />
                        </Link>
                    </Button>
                    <h1 class="truncate font-mono text-lg font-bold">
                        {{ order.order_code }}
                    </h1>
                </div>
                <p class="mt-0.5 text-xs text-muted-foreground sm:text-sm">
                    {{ new Date(order.created_at).toLocaleString() }} by {{ order.creator.name }}
                </p>
            </div>
            <div class="flex shrink-0 items-center gap-2">
                <Button variant="outline" size="sm" class="h-9" as-child>
                    <Link :href="OrderController.edit.url({ order: order.id })">
                        <Pencil class="mr-1 size-3.5" />
                        Edit
                    </Link>
                </Button>
                <Badge
                    variant="secondary"
                    :class="statusColor(currentStatusInfo().color)"
                >
                    {{ currentStatusInfo().label }}
                </Badge>
            </div>
        </div>

        <!-- Customer -->
        <div class="rounded-xl border bg-card p-3 sm:p-4 lg:p-5">
            <h2 class="mb-3 text-xs font-semibold text-muted-foreground uppercase tracking-wider">Customer</h2>
            <div class="space-y-3 sm:grid sm:grid-cols-2 sm:gap-4 sm:space-y-0">
                <div class="flex items-start gap-3">
                    <div class="flex size-9 shrink-0 items-center justify-center rounded-lg bg-primary/10 text-primary">
                        <User class="size-4" />
                    </div>
                    <div class="min-w-0">
                        <p class="text-xs text-muted-foreground">Name</p>
                        <p class="text-sm font-medium">{{ order.customer_name }}</p>
                    </div>
                </div>
                <div class="flex items-start gap-3">
                    <div class="flex size-9 shrink-0 items-center justify-center rounded-lg bg-primary/10 text-primary">
                        <Phone class="size-4" />
                    </div>
                    <div class="min-w-0">
                        <p class="text-xs text-muted-foreground">Phone</p>
                        <p class="text-sm font-medium">{{ order.customer_phone }}</p>
                    </div>
                </div>
                <div class="flex items-start gap-3 sm:col-span-2">
                    <div class="flex size-9 shrink-0 items-center justify-center rounded-lg bg-primary/10 text-primary">
                        <MapPin class="size-4" />
                    </div>
                    <div class="min-w-0">
                        <p class="text-xs text-muted-foreground">Address</p>
                        <p class="text-sm font-medium">{{ order.customer_address }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Order -->
        <div class="rounded-xl border bg-card p-3 sm:p-4 lg:p-5">
            <h2 class="mb-3 text-xs font-semibold text-muted-foreground uppercase tracking-wider">Order</h2>
            <div class="space-y-3 sm:grid sm:grid-cols-3 sm:gap-4 sm:space-y-0">
                <div class="flex items-start gap-3">
                    <div class="flex size-9 shrink-0 items-center justify-center rounded-lg bg-primary/10 text-primary">
                        <Package class="size-4" />
                    </div>
                    <div class="min-w-0">
                        <p class="text-xs text-muted-foreground">Item</p>
                        <p class="text-sm font-medium">{{ order.item_description }}</p>
                    </div>
                </div>
                <div>
                    <p class="text-xs text-muted-foreground">Quantity</p>
                    <p class="mt-0.5 text-lg font-bold">{{ order.quantity }}</p>
                </div>
                <div>
                    <p class="text-xs text-muted-foreground">Total Price</p>
                    <p class="mt-0.5 font-mono text-lg font-bold">
                        {{ Number(order.total_price).toLocaleString() }}
                    </p>
                </div>
            </div>
            <div v-if="order.notes" class="mt-3 rounded-lg bg-muted/50 p-3">
                <p class="text-xs text-muted-foreground">Notes</p>
                <p class="mt-0.5 text-sm">{{ order.notes }}</p>
            </div>
        </div>

        <!-- Update Status -->
        <div class="rounded-xl border bg-card p-3 sm:p-4 lg:p-5">
            <h2 class="mb-3 text-xs font-semibold text-muted-foreground uppercase tracking-wider">Update Status</h2>
            <Select v-model="selectedStatus" :disabled="updatingStatus">
                <SelectTrigger class="h-11 text-base sm:h-9 sm:text-sm" :class="{ 'opacity-60': updatingStatus }">
                    <SelectValue placeholder="Select status" />
                </SelectTrigger>
                <SelectContent>
                    <SelectItem
                        v-for="s in statuses"
                        :key="s.value"
                        :value="s.value"
                    >
                        {{ s.label }}
                    </SelectItem>
                </SelectContent>
            </Select>
        </div>

        <!-- Delete -->
        <div class="rounded-xl border border-destructive/20 bg-destructive/5 p-3 sm:p-4 lg:p-5">
            <h2 class="mb-1 text-sm font-semibold text-destructive">Danger Zone</h2>
            <p class="mb-3 text-xs text-muted-foreground sm:text-sm">
                Permanently delete this order. This cannot be undone.
            </p>
            <Dialog>
                <DialogTrigger as-child>
                    <Button variant="destructive" size="sm" class="h-10 sm:h-9">Delete Order</Button>
                </DialogTrigger>
                <DialogContent>
                    <Form
                        v-bind="OrderController.destroy.form({ order: order.id })"
                        v-slot="{ processing }"
                    >
                        <DialogHeader class="space-y-3">
                            <DialogTitle>Are you sure?</DialogTitle>
                            <DialogDescription>
                                Order <span class="font-mono font-medium">{{ order.order_code }}</span>
                                will be permanently deleted.
                            </DialogDescription>
                        </DialogHeader>
                        <DialogFooter class="mt-6 gap-2">
                            <DialogClose as-child>
                                <Button variant="secondary" class="h-11 sm:h-9">Cancel</Button>
                            </DialogClose>
                            <Button type="submit" variant="destructive" :disabled="processing" class="h-11 sm:h-9">
                                Delete Order
                            </Button>
                        </DialogFooter>
                    </Form>
                </DialogContent>
            </Dialog>
        </div>
    </div>
</template>
