<script setup lang="ts">
import { Form, Head, Link } from '@inertiajs/vue3';
import OrderController from '@/actions/App/Http/Controllers/OrderController';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { index as ordersIndex, create as ordersCreate } from '@/routes/orders';
import { ArrowLeft } from 'lucide-vue-next';

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

    <div class="mx-auto w-full max-w-lg px-4 py-4 sm:py-8">
        <!-- Mobile back + title -->
        <div class="mb-6 flex items-center gap-3">
            <Link :href="ordersIndex.url()" class="flex size-9 items-center justify-center rounded-full bg-muted transition-colors hover:bg-accent sm:hidden">
                <ArrowLeft class="size-4" />
            </Link>
            <h1 class="text-lg font-bold sm:text-xl">New Order</h1>
        </div>

        <Form
            v-bind="OrderController.store.form()"
            v-slot="{ errors, processing }"
        >
            <div class="rounded-2xl border bg-card p-4 shadow-sm sm:p-6">
                <!-- Customer fields -->
                <p class="mb-2.5 text-[11px] font-semibold uppercase tracking-widest text-muted-foreground">Customer</p>

                <div class="space-y-3">
                    <div>
                        <Label for="customer_name" class="mb-1 block text-sm">Name</Label>
                        <Input id="customer_name" name="customer_name" required placeholder="Full name" class="h-10 rounded-lg text-sm" />
                        <InputError :message="errors.customer_name" class="mt-1" />
                    </div>
                    <div>
                        <Label for="customer_phone" class="mb-1 block text-sm">Phone</Label>
                        <Input id="customer_phone" name="customer_phone" required placeholder="09xxxxxxxxx" type="tel" class="h-10 rounded-lg text-sm" />
                        <InputError :message="errors.customer_phone" class="mt-1" />
                    </div>
                    <div>
                        <Label for="customer_address" class="mb-1 block text-sm">Address</Label>
                        <textarea id="customer_address" name="customer_address" required rows="2" placeholder="Delivery address" class="w-full rounded-lg border border-input bg-transparent px-3 py-2 text-sm shadow-xs placeholder:text-muted-foreground focus-visible:outline-none focus-visible:border-ring focus-visible:ring-ring/50 focus-visible:ring-[3px]" />
                        <InputError :message="errors.customer_address" class="mt-1" />
                    </div>
                </div>

                <div class="my-4 border-t sm:my-5" />

                <!-- Order fields -->
                <p class="mb-2.5 text-[11px] font-semibold uppercase tracking-widest text-muted-foreground">Order</p>

                <div class="space-y-3">
                    <div>
                        <Label for="item_description" class="mb-1 block text-sm">Item</Label>
                        <Input id="item_description" name="item_description" required placeholder="e.g. DJ Stick Pro" class="h-10 rounded-lg text-sm" />
                        <InputError :message="errors.item_description" class="mt-1" />
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <Label for="quantity" class="mb-1 block text-sm">Qty</Label>
                            <Input id="quantity" name="quantity" type="number" min="1" default-value="1" required class="h-10 rounded-lg text-sm" />
                            <InputError :message="errors.quantity" class="mt-1" />
                        </div>
                        <div>
                            <Label for="total_price" class="mb-1 block text-sm">Total Price</Label>
                            <Input id="total_price" name="total_price" type="number" min="0" step="0.01" required placeholder="0" class="h-10 rounded-lg text-sm" />
                            <InputError :message="errors.total_price" class="mt-1" />
                        </div>
                    </div>
                    <div>
                        <Label for="amount_paid" class="mb-1 block text-sm">
                            Amount Paid <span class="font-normal text-muted-foreground">(optional)</span>
                        </Label>
                        <Input id="amount_paid" name="amount_paid" type="number" min="0" step="0.01" default-value="0" placeholder="0" class="h-10 rounded-lg text-sm" />
                        <InputError :message="errors.amount_paid" class="mt-1" />
                    </div>
                    <div>
                        <Label for="notes" class="mb-1 block text-sm">
                            Notes <span class="font-normal text-muted-foreground">(optional)</span>
                        </Label>
                        <textarea id="notes" name="notes" rows="2" placeholder="Internal notes..." class="w-full rounded-lg border border-input bg-transparent px-3 py-2 text-sm shadow-xs placeholder:text-muted-foreground focus-visible:outline-none focus-visible:border-ring focus-visible:ring-ring/50 focus-visible:ring-[3px]" />
                        <InputError :message="errors.notes" class="mt-1" />
                    </div>
                </div>

                <Button :disabled="processing" class="mt-5 h-11 w-full rounded-xl text-sm font-semibold shadow-md shadow-primary/25 sm:mt-6 sm:h-10">
                    Create Order
                </Button>
            </div>
        </Form>
    </div>
</template>
