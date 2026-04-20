<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';
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

    <div class="flex min-h-screen flex-col items-center justify-center bg-[#FDFDFC] p-6 dark:bg-[#0a0a0a]">
        <header class="mb-8 w-full max-w-md text-right">
            <nav class="flex items-center justify-end gap-4 text-sm">
                <Link
                    v-if="$page.props.auth?.user"
                    :href="dashboard()"
                    class="inline-block rounded-sm border border-[#19140035] px-5 py-1.5 text-[#1b1b18] hover:border-[#1915014a] dark:border-[#3E3E3A] dark:text-[#EDEDEC] dark:hover:border-[#62605b]"
                >
                    Dashboard
                </Link>
                <Link
                    v-else
                    :href="login()"
                    class="inline-block rounded-sm border border-transparent px-5 py-1.5 text-[#1b1b18] hover:border-[#19140035] dark:text-[#EDEDEC] dark:hover:border-[#3E3E3A]"
                >
                    Team Login
                </Link>
            </nav>
        </header>

        <main class="w-full max-w-md">
            <div class="rounded-lg border bg-white p-8 shadow-sm dark:border-[#3E3E3A] dark:bg-[#161615]">
                <div class="mb-6 text-center">
                    <h1 class="text-2xl font-semibold tracking-tight text-[#1b1b18] dark:text-[#EDEDEC]">
                        Track Your Order
                    </h1>
                    <p class="mt-2 text-sm text-[#706f6c] dark:text-[#A1A09A]">
                        Enter your order code to check the status
                    </p>
                </div>

                <form @submit.prevent="search" class="space-y-4">
                    <div class="grid gap-2">
                        <Label for="code">Order Code</Label>
                        <Input
                            id="code"
                            v-model="code"
                            placeholder="e.g. DJ-ABCD1234"
                            class="text-center font-mono text-lg tracking-widest"
                            required
                        />
                    </div>
                    <Button class="w-full" :disabled="!code.trim()">
                        <Search class="mr-2 size-4" />
                        Track Order
                    </Button>
                </form>
            </div>
        </main>
    </div>
</template>
