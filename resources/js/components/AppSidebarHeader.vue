<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import AppLogoIcon from '@/components/AppLogoIcon.vue';
import Breadcrumbs from '@/components/Breadcrumbs.vue';
import UserMenuContent from '@/components/UserMenuContent.vue';
import { Avatar, AvatarFallback } from '@/components/ui/avatar';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';
import { SidebarTrigger } from '@/components/ui/sidebar';
import { dashboard } from '@/routes';
import type { BreadcrumbItem } from '@/types';

withDefaults(
    defineProps<{
        breadcrumbs?: BreadcrumbItem[];
    }>(),
    {
        breadcrumbs: () => [],
    },
);

const page = usePage();
const user = computed(() => page.props.auth.user);
const initials = computed(() => {
    const name = user.value?.name ?? '';
    return name.split(' ').map((n: string) => n[0]).join('').toUpperCase().slice(0, 2);
});
</script>

<template>
    <header
        class="flex h-14 shrink-0 items-center gap-2 border-b border-sidebar-border/70 px-3 transition-[width,height] ease-linear group-has-data-[collapsible=icon]/sidebar-wrapper:h-12 sm:h-16 sm:px-4 md:px-4"
    >
        <!-- Mobile: hamburger + logo + user avatar -->
        <div class="flex w-full items-center justify-between sm:hidden">
            <div class="flex items-center gap-2">
                <SidebarTrigger class="size-9" />
                <Link :href="dashboard()" class="flex items-center gap-2">
                    <div class="flex size-7 items-center justify-center rounded-lg bg-primary text-primary-foreground">
                        <AppLogoIcon class="size-4" />
                    </div>
                    <span class="text-sm font-semibold">DJ Ready Kit</span>
                </Link>
            </div>
            <DropdownMenu>
                <DropdownMenuTrigger as-child>
                    <button class="flex size-9 items-center justify-center rounded-full bg-primary/10 text-sm font-medium text-primary ring-offset-background transition-colors hover:bg-primary/20 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring">
                        <Avatar class="size-8">
                            <AvatarFallback class="bg-primary/10 text-xs font-semibold text-primary">
                                {{ initials }}
                            </AvatarFallback>
                        </Avatar>
                    </button>
                </DropdownMenuTrigger>
                <DropdownMenuContent class="w-56 rounded-xl" align="end" :side-offset="8">
                    <UserMenuContent :user="user" />
                </DropdownMenuContent>
            </DropdownMenu>
        </div>

        <!-- Desktop: sidebar trigger + breadcrumbs -->
        <div class="hidden items-center gap-2 sm:flex">
            <SidebarTrigger class="-ml-1" />
            <template v-if="breadcrumbs && breadcrumbs.length > 0">
                <Breadcrumbs :breadcrumbs="breadcrumbs" />
            </template>
        </div>
    </header>
</template>
