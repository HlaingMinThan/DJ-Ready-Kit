<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';
import AppLogoIcon from '@/components/AppLogoIcon.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { dashboard, login } from '@/routes';
import OrderTrackController from '@/actions/App/Http/Controllers/OrderTrackController';
import { Search } from 'lucide-vue-next';

defineOptions({ layout: false });

const code = ref('');

function search() {
    if (!code.value.trim()) return;
    router.get(OrderTrackController.show.url({ query: { code: code.value.trim() } }));
}
</script>

<template>
    <Head title="Track Your Order" />

    <div class="flex min-h-screen flex-col items-center justify-center bg-background p-6">
        <header class="mb-8 w-full max-w-md text-right">
            <nav class="flex items-center justify-end gap-3 text-sm">
                <Link
                    v-if="$page.props.auth?.user"
                    :href="dashboard()"
                    class="inline-block rounded-lg bg-primary px-4 py-2 text-sm font-medium text-primary-foreground transition-colors hover:bg-primary/90"
                >
                    Dashboard
                </Link>
                <Link
                    v-else
                    :href="login()"
                    class="inline-block rounded-lg px-4 py-2 text-sm font-medium text-foreground transition-colors hover:bg-accent"
                >
                    Team Login
                </Link>
            </nav>
        </header>

        <main class="w-full max-w-md">
            <div class="overflow-hidden rounded-2xl border bg-card shadow-lg">
                <div class="bg-gradient-to-br from-primary/10 via-primary/5 to-transparent p-6 text-center">
                    <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-2xl bg-primary text-primary-foreground shadow-lg shadow-primary/30">
                        <AppLogoIcon class="size-8" />
                    </div>
                    <h1 class="text-xl font-bold tracking-tight text-foreground">
                        Track Your Order
                    </h1>
                    <p class="mt-1 text-sm text-muted-foreground">
                        Enter your order code to check the status
                    </p>
                </div>

                <div class="border-t p-6">
                    <form @submit.prevent="search" class="space-y-4">
                        <div class="grid gap-1.5">
                            <Label for="code">Order Code</Label>
                            <Input
                                id="code"
                                v-model="code"
                                placeholder="e.g. DJ-ABCD1234"
                                class="text-center font-mono text-lg tracking-widest"
                                required
                            />
                        </div>
                        <Button class="w-full shadow-sm shadow-primary/20" :disabled="!code.trim()">
                            <Search class="mr-2 size-4" />
                            Track Order
                        </Button>
                    </form>
                </div>
            </div>
        </main>
    </div>
</template>
