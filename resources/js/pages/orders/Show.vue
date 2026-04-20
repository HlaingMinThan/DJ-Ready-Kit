<script setup lang="ts">
import { Form, Head } from '@inertiajs/vue3';
import { ref } from 'vue';
import OrderController from '@/actions/App/Http/Controllers/OrderController';
import Heading from '@/components/Heading.vue';
import InputError from '@/components/InputError.vue';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import { index as ordersIndex } from '@/routes/orders';

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

    <div class="mx-auto max-w-2xl space-y-6 p-4">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="font-mono text-xl font-semibold">
                    {{ order.order_code }}
                </h1>
                <p class="text-sm text-muted-foreground">
                    Created {{ new Date(order.created_at).toLocaleString() }}
                    by {{ order.creator.name }}
                </p>
            </div>
            <Badge
                variant="secondary"
                :class="statusColor(currentStatusInfo().color)"
                class="text-sm"
            >
                {{ currentStatusInfo().label }}
            </Badge>
        </div>

        <div class="rounded-lg border p-4">
            <Heading
                variant="small"
                title="Customer Details"
            />
            <dl class="mt-3 grid gap-3 text-sm sm:grid-cols-2">
                <div>
                    <dt class="text-muted-foreground">Name</dt>
                    <dd class="font-medium">{{ order.customer_name }}</dd>
                </div>
                <div>
                    <dt class="text-muted-foreground">Phone</dt>
                    <dd class="font-medium">{{ order.customer_phone }}</dd>
                </div>
                <div class="sm:col-span-2">
                    <dt class="text-muted-foreground">Address</dt>
                    <dd class="font-medium">{{ order.customer_address }}</dd>
                </div>
            </dl>
        </div>

        <div class="rounded-lg border p-4">
            <Heading
                variant="small"
                title="Order Details"
            />
            <dl class="mt-3 grid gap-3 text-sm sm:grid-cols-3">
                <div>
                    <dt class="text-muted-foreground">Item</dt>
                    <dd class="font-medium">{{ order.item_description }}</dd>
                </div>
                <div>
                    <dt class="text-muted-foreground">Quantity</dt>
                    <dd class="font-medium">{{ order.quantity }}</dd>
                </div>
                <div>
                    <dt class="text-muted-foreground">Total Price</dt>
                    <dd class="font-mono font-medium">
                        {{ Number(order.total_price).toLocaleString() }}
                    </dd>
                </div>
            </dl>
            <div v-if="order.notes" class="mt-3">
                <dt class="text-sm text-muted-foreground">Notes</dt>
                <dd class="mt-1 text-sm">{{ order.notes }}</dd>
            </div>
        </div>

        <div class="rounded-lg border p-4">
            <Heading
                variant="small"
                title="Update Status"
            />
            <Form
                v-bind="
                    OrderController.update.form({ order: order.id })
                "
                class="mt-3 flex items-end gap-3"
                v-slot="{ errors, processing }"
            >
                <div class="grid flex-1 gap-2">
                    <Select name="status" v-model="selectedStatus">
                        <SelectTrigger>
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
                    <InputError :message="errors.status" />
                </div>
                <Button :disabled="processing">Update</Button>
            </Form>
        </div>
    </div>
</template>
