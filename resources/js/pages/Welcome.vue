<script setup lang="ts">
import { Head, Link, usePage } from '@inertiajs/vue3';
import { ref, onMounted, onUnmounted } from 'vue';
import AppLogoIcon from '@/components/AppLogoIcon.vue';
import { dashboard, login, register, track } from '@/routes';
import {
    Zap,
    Truck,
    Search,
    ChevronRight,
    Disc3,
    Headphones,
    ListMusic,
    Send,
    Usb,
    Package,
    ArrowRight,
} from 'lucide-vue-next';

withDefaults(
    defineProps<{
        canRegister: boolean;
    }>(),
    {
        canRegister: true,
    },
);

const page = usePage();
const scrolled = ref(false);
const mobileMenuOpen = ref(false);

function onScroll() {
    scrolled.value = window.scrollY > 40;
}

onMounted(() => {
    window.addEventListener('scroll', onScroll, { passive: true });
    onScroll();
});

onUnmounted(() => {
    window.removeEventListener('scroll', onScroll);
});

const features = [
    {
        icon: ListMusic,
        title: '5,000+ Tracks',
        desc: 'Massive library spanning EDM, Vina House, DNB, Happy Vibe, Chinese House, and more.',
    },
    {
        icon: Zap,
        title: 'Plug & Play',
        desc: 'Hot cues pre-set, playlists organized by genre. Plug in your USB and start mixing instantly.',
    },
    {
        icon: Headphones,
        title: 'Pro Quality',
        desc: '64GB USB loaded with high-quality tracks curated for professional DJ sets and events.',
    },
    {
        icon: Truck,
        title: 'Direct Delivery',
        desc: 'Ships direct from Bangkok to Yangon. Fast, reliable delivery right to your door.',
    },
];

const steps = [
    {
        num: '01',
        icon: Send,
        title: 'Message Us',
        desc: 'Contact us on our Telegram channel to start your order.',
    },
    {
        num: '02',
        icon: Package,
        title: 'Get Order Code',
        desc: 'Place your order and receive a unique tracking code.',
    },
    {
        num: '03',
        icon: Search,
        title: 'Track Delivery',
        desc: 'Use your code on our website to check delivery status anytime.',
    },
];
</script>

<template>
    <Head title="DJ Ready Kit Myanmar — Plug & Play DJ USB" />

    <!-- Force dark theme for landing page -->
    <div class="dark">
        <div class="landing-page min-h-screen bg-background text-foreground">

            <!-- ═══════════════ NAVIGATION ═══════════════ -->
            <nav
                :class="[
                    'fixed top-0 left-0 right-0 z-50 transition-all duration-500',
                    scrolled
                        ? 'bg-background/70 shadow-[0_1px_0_hsl(260_12%_16%)] backdrop-blur-2xl'
                        : 'bg-transparent',
                ]"
            >
                <div class="mx-auto flex max-w-6xl items-center justify-between px-5 py-4 sm:px-8">
                    <a href="/" class="group flex items-center gap-2.5">
                        <div class="flex size-9 items-center justify-center rounded-xl bg-primary text-primary-foreground shadow-lg shadow-primary/20 transition-shadow group-hover:shadow-primary/40">
                            <AppLogoIcon class="size-5" />
                        </div>
                        <span class="font-sans text-lg font-bold tracking-tight text-foreground">
                            DJ Ready Kit
                        </span>
                    </a>
                    <div class="flex items-center gap-2 sm:gap-3">
                        <Link
                            v-if="$page.props.auth.user"
                            :href="dashboard()"
                            class="rounded-lg border border-border bg-card/50 px-4 py-2 text-sm font-medium text-foreground transition-all hover:border-primary/30 hover:bg-card"
                        >
                            Dashboard
                        </Link>
                        <Link
                            :href="track.url()"
                            class="group inline-flex items-center gap-2 rounded-lg bg-primary px-4 py-2 text-sm font-semibold text-primary-foreground shadow-md shadow-primary/25 transition-all hover:shadow-lg hover:shadow-primary/40"
                        >
                            <Search class="size-3.5" />
                            Track Order
                        </Link>
                    </div>
                </div>
            </nav>

            <!-- ═══════════════ HERO ═══════════════ -->
            <section class="relative overflow-hidden pt-20 pb-16 sm:pt-24 sm:pb-24 lg:min-h-[100dvh] lg:flex lg:items-center">
                <!-- Background atmosphere -->
                <div class="pointer-events-none absolute inset-0">
                    <div class="absolute top-[-20%] left-1/2 h-[800px] w-[800px] -translate-x-1/2 rounded-full bg-[radial-gradient(circle,hsl(265_90%_68%/0.12)_0%,transparent_70%)]" />
                    <div class="absolute inset-0 opacity-[0.03]" style="background-image: linear-gradient(hsl(265 90% 68%) 1px, transparent 1px), linear-gradient(to right, hsl(265 90% 68%) 1px, transparent 1px); background-size: 80px 80px" />
                </div>

                <div class="relative mx-auto w-full max-w-6xl px-5 sm:px-8">
                    <!-- Mobile: product image first, then text -->
                    <!-- Desktop: text left, product right -->
                    <div class="flex flex-col items-center gap-10 lg:flex-row lg:gap-20">

                        <!-- Product image (shows first on mobile, second on desktop) -->
                        <div class="relative order-1 flex justify-center lg:order-2 lg:flex-1">
                            <div class="product-float relative">
                                <!-- Glow ring behind product -->
                                <div class="absolute top-1/2 left-1/2 h-[280px] w-[280px] -translate-x-1/2 -translate-y-1/2 rounded-full bg-primary/[0.07] blur-[60px] sm:h-[360px] sm:w-[360px]" />
                                <!-- Circular stage -->
                                <div class="relative mx-auto flex size-[260px] items-center justify-center rounded-full border border-primary/10 bg-gradient-to-b from-card/80 to-card/30 shadow-2xl shadow-primary/10 backdrop-blur sm:size-[360px] lg:size-[400px]">
                                    <img
                                        src="/images/dj-usb-stick.png"
                                        alt="DJ Ready Kit — 64GB USB Stick"
                                        class="relative w-[75%] -rotate-12 drop-shadow-[0_10px_30px_rgba(0,0,0,0.5)]"
                                    />
                                </div>
                                <!-- Specs badges around the circle -->
                                <div class="absolute top-2 right-4 rounded-full border border-primary/20 bg-card/80 px-3 py-1 text-xs font-semibold text-primary backdrop-blur sm:top-4 sm:right-2">
                                    64GB
                                </div>
                                <div class="absolute bottom-4 left-2 rounded-full border border-primary/20 bg-card/80 px-3 py-1 text-xs font-semibold text-primary backdrop-blur sm:bottom-6 sm:left-0">
                                    5,000+ Tracks
                                </div>
                            </div>
                        </div>

                        <!-- Text content -->
                        <div class="order-2 text-center lg:order-1 lg:flex-1 lg:text-left hero-stagger">
                            <!-- Badge -->
                            <div class="mb-5 inline-flex items-center gap-2 rounded-full border border-primary/20 bg-primary/[0.08] px-4 py-1.5 text-sm font-medium text-primary hero-item">
                                <Zap class="size-3.5" />
                                Plug & Play DJ Ready
                            </div>

                            <!-- Headline -->
                            <h1 class="text-3xl leading-[1.15] font-bold tracking-tight sm:text-4xl lg:text-5xl hero-item">
                                Your Next Set, <br />
                                <span class="hero-glow-text text-primary">Ready to Drop</span>
                            </h1>

                            <!-- Subtitle -->
                            <p class="mx-auto mt-4 max-w-md text-base leading-relaxed text-muted-foreground lg:mx-0 lg:text-lg hero-item">
                                64GB USB loaded with 5,000+ tracks. EDM, Vina House, DNB, Happy Vibe, Chinese House and more. Hot cues set, playlists organized — just plug in and play.
                            </p>

                            <!-- CTAs -->
                            <div class="mt-7 flex flex-col gap-3 sm:flex-row sm:justify-center lg:justify-start hero-item">
                                <a
                                    href="https://t.me/djreadykitmyanmar"
                                    target="_blank"
                                    rel="noopener"
                                    class="cta-glow group inline-flex items-center justify-center gap-2.5 rounded-xl bg-primary px-7 py-3.5 text-sm font-bold text-primary-foreground shadow-lg shadow-primary/30 transition-all duration-300 hover:shadow-xl hover:shadow-primary/40 active:scale-[0.97] sm:py-3"
                                >
                                    <Send class="size-4 transition-transform group-hover:-translate-y-0.5 group-hover:translate-x-0.5" />
                                    Order via Telegram
                                </a>
                                <Link
                                    :href="track.url()"
                                    class="group inline-flex items-center justify-center gap-2.5 rounded-xl border border-border bg-card/40 px-7 py-3.5 text-sm font-semibold text-foreground backdrop-blur transition-all duration-300 hover:border-primary/25 hover:bg-card/70 active:scale-[0.97] sm:py-3"
                                >
                                    <Search class="size-4 text-muted-foreground transition-colors group-hover:text-primary" />
                                    Track Your Order
                                </Link>
                            </div>

                            <!-- Trust line -->
                            <div class="mt-7 flex items-center justify-center gap-3 text-xs text-muted-foreground lg:justify-start hero-item">
                                <div class="flex -space-x-1">
                                    <div class="size-6 rounded-full border-2 border-background bg-primary/20" />
                                    <div class="size-6 rounded-full border-2 border-background bg-primary/30" />
                                    <div class="size-6 rounded-full border-2 border-background bg-primary/15" />
                                </div>
                                Trusted by DJs across Myanmar
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- ═══════════════ FEATURES ═══════════════ -->
            <section class="relative py-20 sm:py-28">
                <!-- Subtle section divider glow -->
                <div class="pointer-events-none absolute top-0 left-1/2 h-px w-full max-w-2xl -translate-x-1/2 bg-gradient-to-r from-transparent via-primary/20 to-transparent" />

                <div class="mx-auto max-w-6xl px-5 sm:px-8">
                    <div class="text-center">
                        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-primary">Features</p>
                        <h2 class="font-sans mt-3 text-3xl font-bold tracking-tight sm:text-4xl">
                            Why DJs Choose Us
                        </h2>
                    </div>

                    <div class="mt-14 grid gap-4 sm:grid-cols-2 lg:grid-cols-4 sm:gap-5">
                        <div
                            v-for="(f, i) in features"
                            :key="i"
                            class="group relative overflow-hidden rounded-2xl border border-border bg-card/40 p-6 backdrop-blur transition-all duration-300 hover:-translate-y-1 hover:border-primary/25 hover:shadow-xl hover:shadow-primary/[0.07]"
                        >
                            <!-- Card hover glow -->
                            <div class="pointer-events-none absolute inset-0 bg-gradient-to-br from-primary/[0.03] to-transparent opacity-0 transition-opacity duration-300 group-hover:opacity-100" />
                            <div class="relative">
                                <div class="mb-4 flex size-11 items-center justify-center rounded-xl bg-primary/10 text-primary transition-all duration-300 group-hover:bg-primary group-hover:text-primary-foreground group-hover:shadow-lg group-hover:shadow-primary/25">
                                    <component :is="f.icon" class="size-5" />
                                </div>
                                <h3 class="text-base font-bold text-foreground">{{ f.title }}</h3>
                                <p class="mt-2 text-sm leading-relaxed text-muted-foreground">{{ f.desc }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- ═══════════════ HOW IT WORKS ═══════════════ -->
            <section class="relative py-20 sm:py-28">
                <div class="pointer-events-none absolute top-0 left-1/2 h-px w-full max-w-2xl -translate-x-1/2 bg-gradient-to-r from-transparent via-border to-transparent" />

                <div class="mx-auto max-w-4xl px-5 sm:px-8">
                    <div class="text-center">
                        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-primary">Process</p>
                        <h2 class="font-sans mt-3 text-3xl font-bold tracking-tight sm:text-4xl">
                            How It Works
                        </h2>
                    </div>

                    <div class="mt-14 grid gap-8 md:grid-cols-3 md:gap-6">
                        <div
                            v-for="(s, i) in steps"
                            :key="i"
                            class="group relative text-center"
                        >
                            <!-- Connector line (desktop) -->
                            <div
                                v-if="i < steps.length - 1"
                                class="pointer-events-none absolute top-8 left-[calc(50%+2rem)] hidden h-px w-[calc(100%-4rem)] bg-gradient-to-r from-primary/20 to-primary/5 md:block"
                            />

                            <div class="mx-auto mb-5 flex size-16 items-center justify-center rounded-2xl border border-primary/20 bg-primary/[0.08] transition-all duration-300 group-hover:border-primary/40 group-hover:bg-primary/15 group-hover:shadow-lg group-hover:shadow-primary/10">
                                <span class="font-sans text-xl font-bold text-primary">{{ s.num }}</span>
                            </div>
                            <h3 class="text-base font-bold text-foreground">{{ s.title }}</h3>
                            <p class="mx-auto mt-2 max-w-[240px] text-sm leading-relaxed text-muted-foreground">{{ s.desc }}</p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- ═══════════════ TRACK ORDER CTA ═══════════════ -->
            <section class="relative py-20 sm:py-28">
                <div class="pointer-events-none absolute top-0 left-1/2 h-px w-full max-w-2xl -translate-x-1/2 bg-gradient-to-r from-transparent via-border to-transparent" />

                <div class="mx-auto max-w-3xl px-5 sm:px-8">
                    <div class="relative overflow-hidden rounded-3xl border border-primary/15 bg-gradient-to-br from-primary/[0.06] via-card/80 to-card p-8 text-center shadow-[0_0_80px_hsl(265_90%_68%/0.06)] sm:p-14">
                        <!-- Decorative orb -->
                        <div class="pointer-events-none absolute top-[-40%] right-[-20%] h-[300px] w-[300px] rounded-full bg-[radial-gradient(circle,hsl(265_90%_68%/0.08)_0%,transparent_70%)]" />

                        <div class="relative">
                            <div class="mx-auto mb-4 flex size-14 items-center justify-center rounded-2xl bg-primary/10 text-primary">
                                <Search class="size-7" />
                            </div>
                            <h2 class="font-sans text-2xl font-bold tracking-tight sm:text-3xl">
                                Have an Order Code?
                            </h2>
                            <p class="mt-2 text-muted-foreground">
                                Check your delivery status instantly
                            </p>
                            <div class="mt-7">
                                <Link
                                    :href="track.url()"
                                    class="group inline-flex items-center gap-2 rounded-xl bg-primary px-8 py-3.5 text-sm font-bold text-primary-foreground shadow-lg shadow-primary/25 transition-all duration-300 hover:shadow-xl hover:shadow-primary/40 active:scale-[0.97] sm:py-3"
                                >
                                    Track Your Order
                                    <ArrowRight class="size-4 transition-transform group-hover:translate-x-0.5" />
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- ═══════════════ CONNECT / SOCIAL ═══════════════ -->
            <section class="relative py-20 sm:py-28">
                <div class="pointer-events-none absolute top-0 left-1/2 h-px w-full max-w-2xl -translate-x-1/2 bg-gradient-to-r from-transparent via-border to-transparent" />

                <div class="mx-auto max-w-2xl px-5 sm:px-8">
                    <div class="text-center">
                        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-primary">Community</p>
                        <h2 class="font-sans mt-3 text-3xl font-bold tracking-tight sm:text-4xl">
                            Stay Connected
                        </h2>
                    </div>

                    <div class="mt-10 grid gap-4 sm:grid-cols-2">
                        <!-- TikTok -->
                        <a
                            href="https://www.tiktok.com/@djreadykitmyanmar"
                            target="_blank"
                            rel="noopener"
                            class="group flex items-center gap-4 rounded-2xl border border-border bg-card/40 p-5 backdrop-blur transition-all duration-300 hover:-translate-y-0.5 hover:border-primary/25 hover:shadow-lg hover:shadow-primary/[0.07]"
                        >
                            <div class="flex size-11 shrink-0 items-center justify-center rounded-xl bg-primary/10 transition-all group-hover:bg-primary group-hover:shadow-lg group-hover:shadow-primary/25">
                                <!-- TikTok SVG -->
                                <svg class="size-5 text-primary transition-colors group-hover:text-primary-foreground" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M19.59 6.69a4.83 4.83 0 01-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 01-2.88 2.5 2.89 2.89 0 01-2.89-2.89 2.89 2.89 0 012.89-2.89c.28 0 .54.04.79.1v-3.5a6.37 6.37 0 00-.79-.05A6.34 6.34 0 003.15 15.2a6.34 6.34 0 0010.86 4.46V13.2a8.16 8.16 0 005.58 2.17V11.9a4.84 4.84 0 01-3.58-1.62V6.69h3.58z" />
                                </svg>
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="text-sm font-bold text-foreground">TikTok</p>
                                <p class="truncate text-xs text-muted-foreground">@djreadykitmyanmar</p>
                            </div>
                            <ChevronRight class="size-4 shrink-0 text-muted-foreground/50 transition-all group-hover:translate-x-0.5 group-hover:text-primary" />
                        </a>

                        <!-- Telegram -->
                        <a
                            href="https://t.me/djreadykitmyanmarchannel"
                            target="_blank"
                            rel="noopener"
                            class="group flex items-center gap-4 rounded-2xl border border-border bg-card/40 p-5 backdrop-blur transition-all duration-300 hover:-translate-y-0.5 hover:border-primary/25 hover:shadow-lg hover:shadow-primary/[0.07]"
                        >
                            <div class="flex size-11 shrink-0 items-center justify-center rounded-xl bg-primary/10 transition-all group-hover:bg-primary group-hover:shadow-lg group-hover:shadow-primary/25">
                                <Send class="size-5 text-primary transition-colors group-hover:text-primary-foreground" />
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="text-sm font-bold text-foreground">Telegram</p>
                                <p class="truncate text-xs text-muted-foreground">DJ Ready Kit Channel</p>
                            </div>
                            <ChevronRight class="size-4 shrink-0 text-muted-foreground/50 transition-all group-hover:translate-x-0.5 group-hover:text-primary" />
                        </a>
                    </div>
                </div>
            </section>

            <!-- ═══════════════ FOOTER ═══════════════ -->
            <footer class="border-t border-border">
                <div class="mx-auto flex max-w-6xl flex-col items-center gap-5 px-5 py-8 sm:flex-row sm:justify-between sm:px-8">
                    <div class="flex items-center gap-2">
                        <AppLogoIcon class="size-5 text-primary" />
                        <span class="text-sm font-semibold text-foreground">DJ Ready Kit Myanmar</span>
                    </div>
                    <p class="text-xs text-muted-foreground">
                        &copy; {{ new Date().getFullYear() }} DJ Ready Kit Myanmar. All rights reserved.
                    </p>
                    <div class="flex items-center gap-3">
                        <a
                            href="https://www.tiktok.com/@djreadykitmyanmar"
                            target="_blank"
                            rel="noopener"
                            class="flex size-8 items-center justify-center rounded-lg text-muted-foreground transition-colors hover:bg-card hover:text-primary"
                            aria-label="TikTok"
                        >
                            <svg class="size-4" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M19.59 6.69a4.83 4.83 0 01-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 01-2.88 2.5 2.89 2.89 0 01-2.89-2.89 2.89 2.89 0 012.89-2.89c.28 0 .54.04.79.1v-3.5a6.37 6.37 0 00-.79-.05A6.34 6.34 0 003.15 15.2a6.34 6.34 0 0010.86 4.46V13.2a8.16 8.16 0 005.58 2.17V11.9a4.84 4.84 0 01-3.58-1.62V6.69h3.58z" />
                            </svg>
                        </a>
                        <a
                            href="https://t.me/djreadykitmyanmarchannel"
                            target="_blank"
                            rel="noopener"
                            class="flex size-8 items-center justify-center rounded-lg text-muted-foreground transition-colors hover:bg-card hover:text-primary"
                            aria-label="Telegram"
                        >
                            <Send class="size-4" />
                        </a>
                    </div>
                </div>
            </footer>

        </div>
    </div>
</template>

<style scoped>
/* ── Hero stagger entrance ── */
.hero-stagger .hero-item {
    opacity: 0;
    transform: translateY(20px);
    animation: hero-rise 0.7s cubic-bezier(0.16, 1, 0.3, 1) forwards;
}
.hero-stagger .hero-item:nth-child(1) { animation-delay: 0.1s; }
.hero-stagger .hero-item:nth-child(2) { animation-delay: 0.2s; }
.hero-stagger .hero-item:nth-child(3) { animation-delay: 0.35s; }
.hero-stagger .hero-item:nth-child(4) { animation-delay: 0.5s; }
.hero-stagger .hero-item:nth-child(5) { animation-delay: 0.65s; }

@keyframes hero-rise {
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* ── Product image float ── */
.product-float {
    animation: float 6s ease-in-out infinite;
}

@keyframes float {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-12px); }
}

/* ── CTA glow pulse ── */
.cta-glow {
    animation: glow-pulse 3s ease-in-out infinite;
}

@keyframes glow-pulse {
    0%, 100% { box-shadow: 0 10px 30px -5px hsl(265 90% 68% / 0.3); }
    50% { box-shadow: 0 10px 40px -5px hsl(265 90% 68% / 0.5); }
}

/* ── Rotating disc ── */
.disc-spin {
    animation: disc-rotate 40s linear infinite;
}

@keyframes disc-rotate {
    to { transform: rotate(360deg); }
}

/* ── Hero headline glow ── */
.hero-glow-text {
    filter: drop-shadow(0 0 30px hsl(265 90% 68% / 0.3));
}

/* ── Right side hero item entrance ── */
.hero-item {
    opacity: 0;
    transform: translateY(20px);
    animation: hero-rise 0.8s cubic-bezier(0.16, 1, 0.3, 1) 0.4s forwards;
}
</style>
