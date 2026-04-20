<script setup lang="ts">
import { Form, Head } from '@inertiajs/vue3';
import OrderController from '@/actions/App/Http/Controllers/OrderController';
import Heading from '@/components/Heading.vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { index as ordersIndex, create as ordersCreate } from '@/routes/orders';

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'Orders',
                href: ordersIndex.url(),
            },
            {
                title: 'New Order',
                href: ordersCreate.url(),
            },
        ],
    },
});
</script>

<template>
    <Head title="New Order" />

    <div class="mx-auto max-w-2xl p-4">
        <Heading title="Create Order" description="Add a new customer order" />

        <Form
            v-bind="OrderController.store.form()"
            class="space-y-6"
            v-slot="{ errors, processing }"
        >
            <div class="grid gap-4 sm:grid-cols-2">
                <div class="grid gap-2">
                    <Label for="customer_name">Customer Name</Label>
                    <Input
                        id="customer_name"
                        name="customer_name"
                        required
                        placeholder="Full name"
                    />
                    <InputError :message="errors.customer_name" />
                </div>

                <div class="grid gap-2">
                    <Label for="customer_phone">Phone</Label>
                    <Input
                        id="customer_phone"
                        name="customer_phone"
                        required
                        placeholder="Phone number"
                    />
                    <InputError :message="errors.customer_phone" />
                </div>
            </div>

            <div class="grid gap-2">
                <Label for="customer_address">Address</Label>
                <textarea
                    id="customer_address"
                    name="customer_address"
                    required
                    rows="2"
                    placeholder="Delivery address"
                    class="flex w-full rounded-md border border-input bg-transparent px-3 py-2 text-sm shadow-xs placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring"
                />
                <InputError :message="errors.customer_address" />
            </div>

            <div class="grid gap-2">
                <Label for="item_description">Item Description</Label>
                <Input
                    id="item_description"
                    name="item_description"
                    required
                    placeholder="e.g. DJ Stick Pro 2024"
                />
                <InputError :message="errors.item_description" />
            </div>

            <div class="grid gap-4 sm:grid-cols-2">
                <div class="grid gap-2">
                    <Label for="quantity">Quantity</Label>
                    <Input
                        id="quantity"
                        name="quantity"
                        type="number"
                        min="1"
                        default-value="1"
                        required
                    />
                    <InputError :message="errors.quantity" />
                </div>

                <div class="grid gap-2">
                    <Label for="total_price">Total Price</Label>
                    <Input
                        id="total_price"
                        name="total_price"
                        type="number"
                        min="0"
                        step="0.01"
                        required
                        placeholder="0.00"
                    />
                    <InputError :message="errors.total_price" />
                </div>
            </div>

            <div class="grid gap-2">
                <Label for="notes">Notes (optional)</Label>
                <textarea
                    id="notes"
                    name="notes"
                    rows="2"
                    placeholder="Internal notes..."
                    class="flex w-full rounded-md border border-input bg-transparent px-3 py-2 text-sm shadow-xs placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring"
                />
                <InputError :message="errors.notes" />
            </div>

            <div class="flex justify-end">
                <Button :disabled="processing">Create Order</Button>
            </div>
        </Form>
    </div>
</template>
