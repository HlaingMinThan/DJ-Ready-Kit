<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import AppLogoIcon from '@/components/AppLogoIcon.vue';
import { dashboard, login, register } from '@/routes';

withDefaults(
    defineProps<{
        canRegister: boolean;
    }>(),
    {
        canRegister: true,
    },
);
</script>

<template>
    <Head title="Welcome" />
    <div
        class="flex min-h-screen flex-col items-center bg-background p-6 text-foreground lg:justify-center lg:p-8"
    >
        <header
            class="mb-6 w-full max-w-[335px] text-sm not-has-[nav]:hidden lg:max-w-4xl"
        >
            <nav class="flex items-center justify-end gap-3">
                <Link
                    v-if="$page.props.auth.user"
                    :href="dashboard()"
                    class="inline-block rounded-lg bg-primary px-5 py-2 text-sm font-medium text-primary-foreground shadow-sm transition-colors hover:bg-primary/90"
                >
                    Dashboard
                </Link>
                <template v-else>
                    <Link
                        :href="login()"
                        class="inline-block rounded-lg px-5 py-2 text-sm font-medium text-foreground transition-colors hover:bg-accent"
                    >
                        Log in
                    </Link>
                    <Link
                        v-if="canRegister"
                        :href="register()"
                        class="inline-block rounded-lg bg-primary px-5 py-2 text-sm font-medium text-primary-foreground shadow-sm transition-colors hover:bg-primary/90"
                    >
                        Register
                    </Link>
                </template>
            </nav>
        </header>
        <div
            class="flex w-full items-center justify-center opacity-100 transition-opacity duration-750 lg:grow starting:opacity-0"
        >
            <main class="w-full max-w-[335px] lg:max-w-xl">
                <div class="overflow-hidden rounded-2xl border bg-card shadow-lg">
                    <div class="bg-gradient-to-br from-primary/10 via-primary/5 to-transparent p-8 text-center lg:p-12">
                        <div class="mx-auto mb-6 flex h-16 w-16 items-center justify-center rounded-2xl bg-primary text-primary-foreground shadow-lg shadow-primary/30">
                            <AppLogoIcon class="size-9" />
                        </div>
                        <h1 class="text-2xl font-bold tracking-tight lg:text-3xl">
                            DJ Ready Kit
                        </h1>
                        <p class="mt-2 text-sm text-muted-foreground lg:text-base">
                            Order tracking system for your DJ stick business
                        </p>
                    </div>
                    <div class="border-t p-6 lg:p-8">
                        <div class="space-y-3">
                            <Link
                                :href="$page.props.auth.user ? dashboard() : login()"
                                class="flex w-full items-center justify-center rounded-xl bg-primary px-6 py-3 text-sm font-semibold text-primary-foreground shadow-sm transition-all hover:bg-primary/90 hover:shadow-md active:scale-[0.98]"
                            >
                                {{ $page.props.auth.user ? 'Go to Dashboard' : 'Get Started' }}
                            </Link>
                            <a
                                href="/track"
                                class="flex w-full items-center justify-center rounded-xl border bg-card px-6 py-3 text-sm font-semibold text-foreground transition-all hover:bg-accent active:scale-[0.98]"
                            >
                                Track Your Order
                            </a>
                        </div>
                    </div>
                </div>
            </main>
        </div>
        <div class="hidden h-14.5 lg:block"></div>
    </div>
</template>
