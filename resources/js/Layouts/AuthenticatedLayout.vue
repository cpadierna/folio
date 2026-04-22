<script setup>
import { ref, watch, onMounted, onUnmounted, nextTick } from 'vue';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import Toast from '@/Components/Toast.vue';
import { Link } from '@inertiajs/vue3';

const mobileMenuOpen = ref(false);
const overlayRef = ref(null);

function closeMenu() {
    mobileMenuOpen.value = false;
}

function handleKeydown(e) {
    if (!mobileMenuOpen.value) return;
    if (e.key === 'Escape') {
        closeMenu();
        return;
    }
    if (e.key === 'Tab') {
        const focusable = overlayRef.value?.querySelectorAll(
            'a[href], button:not([disabled]), [tabindex]:not([tabindex="-1"])'
        );
        if (!focusable?.length) return;
        const first = focusable[0];
        const last = focusable[focusable.length - 1];
        if (e.shiftKey) {
            if (document.activeElement === first) {
                e.preventDefault();
                last.focus();
            }
        } else {
            if (document.activeElement === last) {
                e.preventDefault();
                first.focus();
            }
        }
    }
}

onMounted(() => {
    document.addEventListener('keydown', handleKeydown);
});

onUnmounted(() => {
    document.removeEventListener('keydown', handleKeydown);
    document.body.style.overflow = '';
});

watch(mobileMenuOpen, (val) => {
    document.body.style.overflow = val ? 'hidden' : '';
    if (val) {
        nextTick(() => {
            overlayRef.value?.querySelector('a[href], button:not([disabled])')?.focus();
        });
    }
});
</script>

<template>
    <div>
        <div class="min-h-screen bg-gray-100">
            <nav
                class="border-b border-gray-100 bg-white"
                aria-label="Main navigation"
            >
                <!-- Primary Navigation Menu -->
                <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <div class="flex h-16 justify-between">
                        <div class="flex">
                            <!-- Logo -->
                            <div class="flex shrink-0 items-center">
                                <Link :href="route('dashboard')" class="flex items-center gap-2 focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500 focus-visible:ring-offset-2 focus-visible:rounded">
                                    <ApplicationLogo
                                        class="block h-9 w-auto fill-current"
                                    />
                                    <span class="text-lg font-bold tracking-tight text-indigo-600">Folio</span>
                                </Link>
                            </div>

                            <!-- Navigation Links -->
                            <div
                                class="hidden space-x-8 xl:-my-px xl:ms-10 xl:flex"
                            >
                                <NavLink
                                    :href="route('dashboard')"
                                    :active="route().current('dashboard')"
                                >
                                    Dashboard
                                </NavLink>
                                <NavLink :href="route('library')" :active="route().current('library')">
                                    Library
                                </NavLink>
                                <NavLink :href="route('books.search')" :active="route().current('books.search')">
                                    Search
                                </NavLink>
                                <NavLink :href="route('feed')" :active="route().current('feed')">
                                    Feed
                                </NavLink>
                                <NavLink :href="route('recommendations')" :active="route().current('recommendations')">
                                    For You
                                </NavLink>
                                <NavLink :href="route('users.search')" :active="route().current('users.search')">
                                    People
                                </NavLink>
                            </div>
                        </div>

                        <div class="hidden xl:ms-6 xl:flex xl:items-center">
                            <!-- Settings Dropdown -->
                            <div class="relative ms-3">
                                <Dropdown align="right" width="48">
                                    <template #trigger>
                                        <span class="inline-flex rounded-md">
                                            <button
                                                type="button"
                                                class="inline-flex items-center gap-2 rounded-md border border-transparent bg-white px-3 py-2 text-sm font-medium leading-4 text-gray-500 transition duration-150 ease-in-out hover:text-gray-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500 focus-visible:ring-offset-2"
                                            >
                                                <img
                                                    v-if="$page.props.auth.user.avatar_url"
                                                    :src="$page.props.auth.user.avatar_url"
                                                    :alt="$page.props.auth.user.name + ' avatar'"
                                                    class="w-7 h-7 rounded-full object-cover"
                                                />
                                                <span
                                                    v-else
                                                    class="w-7 h-7 rounded-full bg-gray-200 flex items-center justify-center text-xs font-semibold text-gray-600"
                                                    aria-hidden="true"
                                                >{{ $page.props.auth.user.name.charAt(0).toUpperCase() }}</span>

                                                {{ $page.props.auth.user.name }}

                                                <svg
                                                    class="-me-0.5 h-4 w-4"
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    viewBox="0 0 20 20"
                                                    fill="currentColor"
                                                >
                                                    <path
                                                        fill-rule="evenodd"
                                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                        clip-rule="evenodd"
                                                    />
                                                </svg>
                                            </button>
                                        </span>
                                    </template>

                                    <template #content>
                                        <DropdownLink
                                            :href="route('profile.edit')"
                                        >
                                            Account Settings
                                        </DropdownLink>
                                        <DropdownLink :href="route('users.show', { user: $page.props.auth.user.id })">
                                            My Profile
                                        </DropdownLink>
                                        <DropdownLink
                                            :href="route('logout')"
                                            method="post"
                                            as="button"
                                        >
                                            Log Out
                                        </DropdownLink>
                                    </template>
                                </Dropdown>
                            </div>
                        </div>

                        <!-- Hamburger (mobile only) -->
                        <div class="-me-2 flex items-center xl:hidden">
                            <button
                                @click="mobileMenuOpen = !mobileMenuOpen"
                                :aria-label="mobileMenuOpen ? 'Close menu' : 'Open menu'"
                                :aria-expanded="mobileMenuOpen"
                                aria-controls="mobile-overlay"
                                class="inline-flex items-center justify-center rounded-md p-2 text-gray-400 transition duration-150 ease-in-out hover:bg-gray-100 hover:text-gray-500 focus:bg-gray-100 focus:text-gray-500 focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500 focus-visible:ring-offset-2"
                            >
                                <svg
                                    v-if="!mobileMenuOpen"
                                    class="h-6 w-6"
                                    stroke="currentColor"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    aria-hidden="true"
                                >
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                </svg>
                                <svg
                                    v-else
                                    class="h-6 w-6"
                                    stroke="currentColor"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    aria-hidden="true"
                                >
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

            </nav>

            <!-- Page Heading -->
            <header
                class="bg-white shadow"
                v-if="$slots.header"
            >
                <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
                    <slot name="header" />
                </div>
            </header>

            <!-- Page Content -->
            <main id="main-content">
                <slot />
            </main>
        </div>
        <Toast />

        <!-- Mobile Fullscreen Overlay -->
        <Teleport to="body">
            <Transition name="mobile-menu">
                <div
                    v-if="mobileMenuOpen"
                    id="mobile-overlay"
                    ref="overlayRef"
                    class="sm:hidden fixed inset-0 z-50 flex flex-col bg-white"
                    role="dialog"
                    aria-modal="true"
                    aria-label="Navigation menu"
                >
                    <!-- Overlay Header -->
                    <div class="flex h-16 shrink-0 items-center justify-between border-b border-gray-100 px-4">
                        <Link
                            :href="route('dashboard')"
                            @click="closeMenu"
                            class="flex items-center gap-2 focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500 focus-visible:ring-offset-2 focus-visible:rounded"
                        >
                            <ApplicationLogo class="block h-9 w-auto fill-current" />
                            <span class="text-lg font-bold tracking-tight text-indigo-600">Folio</span>
                        </Link>
                        <button
                            @click="closeMenu"
                            aria-label="Close menu"
                            class="inline-flex items-center justify-center rounded-md p-2 text-gray-400 transition hover:bg-gray-100 hover:text-gray-500 focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500 focus-visible:ring-offset-2"
                        >
                            <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <!-- Overlay Nav Links -->
                    <nav class="flex-1 overflow-y-auto px-6 pt-2" aria-label="Mobile navigation">
                        <Link
                            :href="route('dashboard')"
                            @click="closeMenu"
                            class="flex w-full items-center border-b border-gray-50 py-4 text-2xl font-medium transition"
                            :class="route().current('dashboard') ? 'text-indigo-600' : 'text-gray-900 hover:text-indigo-600'"
                        >
                            Dashboard
                        </Link>
                        <Link
                            :href="route('library')"
                            @click="closeMenu"
                            class="flex w-full items-center border-b border-gray-50 py-4 text-2xl font-medium transition"
                            :class="route().current('library') ? 'text-indigo-600' : 'text-gray-900 hover:text-indigo-600'"
                        >
                            Library
                        </Link>
                        <Link
                            :href="route('books.search')"
                            @click="closeMenu"
                            class="flex w-full items-center border-b border-gray-50 py-4 text-2xl font-medium transition"
                            :class="route().current('books.search') ? 'text-indigo-600' : 'text-gray-900 hover:text-indigo-600'"
                        >
                            Search
                        </Link>
                        <Link
                            :href="route('feed')"
                            @click="closeMenu"
                            class="flex w-full items-center border-b border-gray-50 py-4 text-2xl font-medium transition"
                            :class="route().current('feed') ? 'text-indigo-600' : 'text-gray-900 hover:text-indigo-600'"
                        >
                            Feed
                        </Link>
                        <Link
                            :href="route('recommendations')"
                            @click="closeMenu"
                            class="flex w-full items-center border-b border-gray-50 py-4 text-2xl font-medium transition"
                            :class="route().current('recommendations') ? 'text-indigo-600' : 'text-gray-900 hover:text-indigo-600'"
                        >
                            For You
                        </Link>
                        <Link
                            :href="route('users.search')"
                            @click="closeMenu"
                            class="flex w-full items-center border-b border-gray-50 py-4 text-2xl font-medium transition"
                            :class="route().current('users.search') ? 'text-indigo-600' : 'text-gray-900 hover:text-indigo-600'"
                        >
                            People
                        </Link>
                    </nav>

                    <!-- Overlay Footer -->
                    <div class="mt-auto shrink-0 border-t border-gray-100 px-6 py-6">
                        <div class="mb-4">
                            <p class="text-sm font-medium text-gray-800">{{ $page.props.auth.user.name }}</p>
                            <p class="text-xs text-gray-400">{{ $page.props.auth.user.email }}</p>
                        </div>
                        <div class="space-y-1">
                            <Link
                                :href="route('profile.edit')"
                                @click="closeMenu"
                                class="flex w-full items-center py-2 text-sm font-medium text-gray-700 transition hover:text-indigo-600 focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500 focus-visible:ring-offset-2 focus-visible:rounded"
                            >
                                Account Settings
                            </Link>
                            <Link
                                :href="route('users.show', { user: $page.props.auth.user.id })"
                                @click="closeMenu"
                                class="flex w-full items-center py-2 text-sm font-medium text-gray-700 transition hover:text-indigo-600 focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500 focus-visible:ring-offset-2 focus-visible:rounded"
                            >
                                My Profile
                            </Link>
                            <Link
                                :href="route('logout')"
                                method="post"
                                as="button"
                                class="flex w-full items-center py-2 text-sm font-medium text-red-500 transition hover:text-red-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-red-500 focus-visible:ring-offset-2 focus-visible:rounded"
                            >
                                Log Out
                            </Link>
                        </div>
                    </div>
                </div>
            </Transition>
        </Teleport>
    </div>
</template>

<style scoped>
.mobile-menu-enter-active,
.mobile-menu-leave-active {
    transition: transform 0.2s ease, opacity 0.2s ease;
}
.mobile-menu-enter-from,
.mobile-menu-leave-to {
    transform: translateX(100%);
    opacity: 0;
}
</style>
